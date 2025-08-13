const getPDF = async (id) => {
    try {
        const data = await apiFetch(`/api/orders/${id}/pdf`, {
            method: 'GET',
            responseType: 'text'
        })
        return `data:application/pdf;base64,${data}`
    } catch (error) {
        console.error("Failed to fetch PDF:", error)
    }
}

export {
    getPDF,
}
