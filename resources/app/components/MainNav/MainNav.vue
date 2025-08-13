<template>
    <div
        class="nav-wrap transition-[left] flex fixed top-0 bg-zinc-800 z-20 w-[200px] h-full lg:h-[48px] lg:w-full lg:left-0"
        :class="navShown ? 'left-0' : 'left-[-200px]'"
    >
        <base-button
            type="button"
            class="btn-toggle flex flex-col justify-center gap-1 bg-zinc-600 hover:bg-zinc-700 lg:hidden"
            @click="toggleNav"
        >
            <i class="bar-line border-b w-full"></i>
            <i class="bar-line border-b w-full"></i>
            <i class="bar-line border-b w-full"></i>
        </base-button>
        <div
            class="flex flex-col justify-between lg:flex-row w-full overflow-auto lg:overflow-visible"
        >
            <div class="flex flex-col lg:flex-row text-white">
                <!-- Root routes -->
                <router-link
                    v-for="(route, index) in navContainer.root"
                    :key="index"
                    class="py-4 px-4 leading-none"
                    :to="route.path"
                    exact-active-class="text-yellow-500"
                    @click="resetPos"
                >
                    {{ startCase(route.name) }}
                </router-link>

                <!-- Components sub-nav -->
                <div class="sub-nav-wrap relative">
                    <button
                        class="sub-nav-btn text-left px-4 h-[48px] leading-none"
                        @click="toggleSubNav"
                    >
                        Components
                    </button>
                    <nav
                        class="sub-nav flex flex-col bg-zinc-700 w-full z-50 overflow-y-hidden mb-4 lg:absolute lg:max-h-0"
                        :class="{ 'max-h-[200px]': subNavShown }"
                    >
                        <router-link
                            v-for="(route, index) in navContainer.components"
                            :key="index"
                            class="pl-8 py-2 lg:pl-4"
                            :to="route.path"
                            exact-active-class="bg-zinc-500"
                            @click="resetPos"
                        >
                            {{ startCase(route.name) }}
                        </router-link>
                    </nav>
                </div>

                <!-- Stats sub-nv (only if admin) -->
                <div class="sub-nav-wrap relative">
                    <button
                        class="sub-nav-btn text-left px-4 h-[48px] leading-none w-[160px]"
                        @click="toggleSubNav"
                    >
                        Download Stats
                    </button>
                    <nav
                        class="sub-nav flex flex-col bg-zinc-700 w-full z-50 overflow-y-hidden mb-4 lg:absolute lg:max-h-0"
                        :class="{ 'max-h-[200px]': subNavShown }"
                    >
                        <base-button
                            v-if="isAdmin"
                            v-for="(stat, idx) in statsTypes"
                            :key="idx"
                            class="text-left px-8 h-[40px] hover:bg-neutral-200 hover:text-neutral-950"
                            :aria-label="'Download Stats for ' + stat.label"
                            @click="getStats(stat.dataType)"
                        >
                            {{ stat.label }}
                        </base-button>
                    </nav>
                </div>
            </div>

            <status-bar class="nav-aside" />
            <slot />
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref, computed } from "vue"
    import { useStore } from "vuex"
    import { useRouter } from "vue-router"
    import { startCase } from "lodash-es"

    /**
     * 1) Define a custom `meta` interface if you need additional fields like `auth`, `type`, `user`, etc.
     * 2) Extend RouteRecordRaw so the compiler knows that name/path/meta may exist.
     */

    // State refs
    const navShown = ref(false)
    const subNavShown = ref(false)

    // Vuex store + router
    const store = useStore()
    const router = useRouter()

    // Cast router.options.routes as our CustomRoute[]
    const routes = router.options.routes as CustomRoute[]

    const hiddenNav = ["orderEdit"]

    const auth = computed(() => store.state.auth)
    const stats = computed(() => store.state.stats)
    const isAdmin = computed(() => store.state.auth.user.role.name === "admin")

    const statsTypes = [
        { label: "Commissions", dataType: "commissions-data" },
        { label: "Products", dataType: "products-data" },
        { label: "Materials", dataType: "materials-data" },
        { label: "Services", dataType: "services-data" },
        { label: "Tax Deducted", dataType: "tax-deducted-orders" },
    ]

    /**
     * Build a nav container:
     * nav.root      -> array of 'root' routes
     * nav.components -> array of 'components' routes
     */
    const navContainer = computed(() => {
        const nav = {
            root: [] as CustomRoute[],
            components: [] as CustomRoute[],
        }

        for (const route of routes) {
            // Skip 404
            if (route.name === "404") continue

            // Make sure meta.type is defined, route is enabled, route has a name, and it's not hidden
            if (
                route.meta?.type &&
                enabled(route) &&
                route.name &&
                !hiddenNav.includes(route.name)
            ) {
                // Push into either nav.root or nav.components
                nav[route.meta.type].push(route)
            }
        }
        return nav
    })

    /**
     * A helper to decide if a route is “enabled” based on auth and route.meta
     */
    const enabled = (route: CustomRoute) => {
        // If user not logged in, only allow routes that do not require auth
        if (!auth.value.loggedIn) {
            return !route.meta?.auth
        }

        // If route requires admin but user is not an admin, disable
        if (route.meta?.user === "admin" && auth.value.user.role.name !== "admin") {
            return false
        }

        // Otherwise return true if `auth` is not explicitly false
        return route.meta?.auth !== false
    }

    // Methods to show/hide nav
    const resetPos = () => {
        subNavShown.value = false
        navShown.value = false
    }

    const toggleNav = () => {
        navShown.value = !navShown.value
    }

    const toggleSubNav = () => {
        subNavShown.value = !subNavShown.value
    }

    // Dispatch action to get stats
    const getStats = (dataType: string) => {
        store.dispatch("getStats", dataType)
    }
</script>
