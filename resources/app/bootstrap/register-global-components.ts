import { defineAsyncComponent } from 'vue'
import type { App } from 'vue'

import MainNav from '@components/MainNav/MainNav.vue'
import MainLayout from '@app/MainLayout.vue'
import StatusBar from '@app/nav/StatusBar.vue'
import Notification from '@app/notifications/Notification.vue'

export default (app: App) => {
    app.component('main-layout', MainLayout)
    app.component('main-nav', MainNav)
    app.component('status-bar', StatusBar)
    app.component('notification', Notification)


    app.component('order-components', defineAsyncComponent(() => import('@app/orders/layouts/OrderComponents.vue')))
    // Components
    app.component('spinner', defineAsyncComponent(() => import('@components/Spinner.vue')))
    app.component('pagination', defineAsyncComponent(() => import('@components/Pagination.vue')))
    app.component('items-options', defineAsyncComponent(() => import('@components/ItemsOptions.vue')))
    app.component('options', defineAsyncComponent(() => import('@components/Options.vue')))
    app.component('active-tabs', defineAsyncComponent(() => import('@components/ActiveTabs.vue')))
    app.component('action-buttons', defineAsyncComponent(() => import('@components/buttons/ActionButtons.vue')))
    app.component('view-buttons', defineAsyncComponent(() => import('@components/buttons/ViewButtons.vue')))
    app.component('modal', defineAsyncComponent(() => import('@components/Modal.vue')))
    app.component('edit-row', defineAsyncComponent(() => import('@components/EditRow.vue')))
    app.component('view-row', defineAsyncComponent(() => import('@components/ViewRow.vue')))

    //  Form Components
    app.component('input-field', defineAsyncComponent(() => import('@components/form-components/InputField.vue')))
    app.component('text-area', defineAsyncComponent(() => import('@components/form-components/TextArea.vue')))
    app.component('input-search', defineAsyncComponent(() => import('@components/form-components/InputSearch.vue')))
    app.component('display-field', defineAsyncComponent(() => import('@components/form-components/DisplayField.vue')))
    app.component('select-field', defineAsyncComponent(() => import('@components/form-components/SelectField.vue')))
}
