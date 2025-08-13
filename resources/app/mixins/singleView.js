import {ref, } from 'vue'
import {useStore} from 'vuex'
import {useRoute} from 'vue-router'

export default function useSingleView() {
    const store = useStore()
    const ignore = ['id', 'created_at', 'updated_at']
    const edit = ref(false)
    const showModal = ref(false)
    const itemClone = ref({})
    const routeName = useRoute().name


    const save = async (args) => {

        await store.dispatch('updateItemAction', {...args})
        edit.value = false
    }
    const remove = async (item) => {
        await store.dispatch('deleteItemAction', item)
        showModal.value = false
    }

    return {
        showModal,
        ignore,
        itemClone,
        edit,
        save,
        remove,
    }
}
