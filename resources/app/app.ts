import { createApp, watch } from 'vue'
import router from '@root/resources/app/lib/router'
import store from '@root/resources/app/store/rootStore'
import App from '@app/App.vue'
import GlobalHelpers from '@root/resources/app/lib/global-helpers'

import registerBaseComponents from '@/app/bootstrap/register-base-components'
import registerGlobalComponents from '@/app/bootstrap/register-global-components'

if (
    store.state.auth.expires_at &&
    store.state.auth.expires_at < Date.now()
) {
    store.commit('logout')
}

const app = createApp(App)
app.use(store).use(router)
app.use(GlobalHelpers)

registerBaseComponents(app)
registerGlobalComponents(app)

watch(
    () => store.state.auth.loggedIn,
    (newLoggedIn) => {
        if (!newLoggedIn) {
            router.push('/login')
        }
    }
)

app.mount('#app')
