
declare module 'vue' {
    import { CompatVue } from '@vue/runtime-dom'
    const Vue: CompatVue
    export default Vue
    export * from '@vue/runtime-dom'
    const { configureCompat } = Vue
    export { configureCompat }
}

declare module "vuex" {
    export * from "vuex/types/index.d.ts"
    export * from "vuex/types/helpers.d.ts"
    export * from "vuex/types/logger.d.ts"
    export * from "vuex/types/vue.d.ts"
}
