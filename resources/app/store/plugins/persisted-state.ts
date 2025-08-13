import createPersistedState from 'vuex-persistedstate'

export default createPersistedState({
    paths: ['auth'],
    getState: (key: string): any => {
        const state = localStorage.getItem(key)
        return state ? JSON.parse(state) : undefined
    },
    setState: (key: string, state: any): void => {
        localStorage.setItem(key, JSON.stringify(state))
    },
})
