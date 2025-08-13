import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRoute, useRouter } from 'vue-router'


export default function useIndexView() {
    const showNewItemModal = ref<boolean>(false)
    const edit = ref<boolean>(false)
    const loading = ref<boolean>(true)
    const searchQuery = ref<string>('')
    const store = useStore()
    const route = useRoute()
    const router = useRouter()

    const fetchData = async () => {
        try {
            await store.dispatch('fetchData', { route })
        } catch (error) {
            console.error('Error in fetchData:', error)
        }
        loading.value = false
    }

    const pageSwitch = (page: string | number): void => {
        router.push({
            query: {
                ...route.query, // Keep existing query params
                page: page.toString() // Overwrite just the page param
            }
        })
    }

    return {
        showNewItemModal,
        edit,
        loading,
        searchQuery,
        fetchData,
        pageSwitch,
    }
}
