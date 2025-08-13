import { createStore, Store } from 'vuex'
import * as actions from '@store/actions'
import items from '@store/modules/items/items-state'
import order from '@root/resources/app/store/modules/orders/order-module'
import stats from '@store/modules/stats/stats-state'
import * as Mutations from '@store/mutations'
import PersistedState from '@store/plugins/persisted-state.js'
import user from '@store/emptyUser'
import type { MutationTree } from 'vuex'

const mutations: MutationTree<RootState> = Mutations
const store: Store<RootState> = createStore({
    state: {
        auth: {
            loggedIn: null,
            user: user,
            roles: [],
            access_token: null,
            expires_at: null,
            timer: 0,
            algolia: {
                app_id: null,
                secret: null,
                initIndex: null,
            },
        },
        notification: {
            message: null,
            type: null,
            info: null,
            error: null,
            errors: [],
            style: null,
            display: false,
        },
        modal: {
            message: null,
            display: false,
        },
        pagination: {
            current_page: 0,
            last_page: 0,
            first_page_url: '',
            last_page_url: '',
            next_page_url: '',
            prev_page_url: '',
            per_page: 0,
            from: 0,
            to: 0,
            total: 0,
        },
    },
    mutations,
    actions,
    modules: {
        items,
        order,
        stats,
    },
    plugins: [PersistedState],
    strict: process.env.NODE_ENV !== 'production',
})

export default store
