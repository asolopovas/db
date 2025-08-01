import { ref } from "vue"

export function useConfirmation() {
    const confirmationModal = ref(false)
    const itemToDelete = ref<any>(null)
    const parentToDelete = ref<any>(null)

    function promptDeletion(item: any, parent: any = null) {
        itemToDelete.value = item
        parentToDelete.value = parent
        confirmationModal.value = true
    }

    function confirmDeletion(callback: (item: any, parent: any) => void) {
        if (itemToDelete.value) {
            callback(itemToDelete.value, parentToDelete.value)
            reset()
        }
    }

    function cancelDeletion() {
        reset()
    }

    function reset() {
        itemToDelete.value = null
        parentToDelete.value = null
        confirmationModal.value = false
    }

    return {
        confirmationModal,
        promptDeletion,
        confirmDeletion,
        cancelDeletion,
    }
}
