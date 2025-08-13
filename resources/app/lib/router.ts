import { createRouter, createWebHistory } from 'vue-router'
import type { RouteRecordRaw, LocationQuery } from 'vue-router'
import store from '@root/resources/app/store/rootStore'
import qs from 'qs'

const Home = () => import('@app/Home.vue')
const Login = () => import('@app/Login.vue')
const Settings = () => import('@app/Settings.vue')
const Items = () => import('@app/Items.vue')
const Users = () => import('@app/Items.vue')
const Orders = () => import('@app/Orders.vue')
const OrderEdit = () => import('@app/orders/OrderEdit.vue')
const Details = () => import('@app/orders/components/Details.vue')
const TaxDeductions = () => import('@app/orders/components/TaxDeductions.vue')
const Materials = () => import('@app/orders/components/Materials.vue')
const Products = () => import('@app/orders/components/Products.vue')
const Services = () => import('@app/orders/components/Services.vue')
const Expenses = () => import('@app/orders/components/Expenses.vue')
const Payments = () => import('@app/orders/components/Payments.vue')
const Areas = () => import('@app/orders/components/Areas.vue')
const NotFound = () => import('@app/NotFound.vue')

const routes: Array<RouteRecordRaw> = [
    { path: '/', name: 'home', meta: { auth: true, type: 'root' }, component: Home },
    { path: '/areas', name: 'areas', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/companies', name: 'companies', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/customers', name: 'customers', meta: { auth: true, type: 'root' }, component: Items },
    { path: '/dimensions', name: 'dimensions', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/extras', name: 'extras', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/floors', name: 'floors', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/grades', name: 'grades', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/login', name: 'login', meta: { auth: false, type: 'root' }, component: Login },
    { path: '/materials', name: 'materials', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/orders', name: 'orders', meta: { auth: true, type: 'root' }, component: Orders },
    {
        path: '/orders/:id',
        name: 'orderEdit',
        meta: { auth: true, hidden: true },
        component: OrderEdit,
        children: [
            { name: 'orderAreas', path: 'areas', component: Areas },
            { name: 'orderDetails', path: 'details', component: Details },
            { name: 'orderExpenses', path: 'expenses', component: Expenses },
            { name: 'orderMaterials', path: 'materials', component: Materials },
            { name: 'orderPayments', path: 'payments', component: Payments },
            { name: 'orderProducts', path: 'products', component: Products },
            { name: 'orderServices', path: 'services', component: Services },
            { name: 'orderTaxDeductions', path: 'tax-deductions', component: TaxDeductions },
        ],
    },
    { path: '/projects', name: 'projects', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/services', name: 'services', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/settings', name: 'settings', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/settings-dashboard', name: 'options', meta: { auth: true, type: 'root' }, component: Settings },
    { path: '/statuses', name: 'statuses', meta: { auth: true, type: 'components' }, component: Items },
    { path: '/users', name: 'users', meta: { auth: true, type: 'root', user: 'admin' }, component: Users },
    { path: '/:pathMatch(.*)*', name: '404', component: NotFound },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    parseQuery: (query: string): LocationQuery => {
        return qs.parse(query, { allowDots: true }) as LocationQuery
    },
    stringifyQuery: (query) => {
        const result = qs.stringify(query, { encode: false, skipNulls: true })
        return result ? `${ result }` : ''
    },
})

router.beforeEach((to, from, next) => {
    if (to.matched.some((record) => record.meta.auth && !store.state.auth.loggedIn)) {
        next({ path: '/login' })
    } else if (to.meta.user === 'admin' && store.state.auth.user?.role?.name !== 'admin') {
        next({ path: '/404' })
    } else {
        next()
    }
})

export default router
