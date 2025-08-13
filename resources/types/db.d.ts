import type { RouteRecordRaw } from "vue-router"


declare global {
    type ChartKey = "products" | "orders"
    type ContactInfo = {
        email1: string
        email2?: string
        email3?: string
        phone: string
        mobile_phone: string
        home_phone?: string
        work_phone?: string
        fax?: string
    }

    type Nullable<T> = {
        [P in keyof T]: T[P] | null
    }

    type Model = {
        id?: number
        created_at?: string
        updated_at?: string
    }

    type Address = {
        street?: string
        town?: string
        county?: string
        city?: string
        postcode?: string
        country?: string
    }

    interface AlgoliaState {
        app_id: string | null
        secret: string | null
        initIndex: string | null
    }

    interface AuthState {
        loggedIn: boolean | null
        user: typeof user
        roles: string[]
        access_token: string | null
        expires_at: number | null
        timer: number
        algolia: AlgoliaState
    }

    interface CreateItemParams {
        item: Item
        routeName: string
    }
    interface CustomMeta {
        type?: "root" | "components"
        auth?: boolean
        user?: string
    }

    interface CustomRoute extends Omit<RouteRecordRaw, "meta" | "name"> {
        // override meta and name to let TS know they can exist
        meta?: CustomMeta
        name?: string
        path: string
    }

    interface DataSet {
        label: string
        borderColor: string
        backgroundColor: string
        data: number[]
    }

    interface DeleteItemParams {
        id: string | number
        routeName: string
    }

    interface FetchParams<T = Record<string, any>> {
        route?: {
            name: string
            query: T
        }
        endpoint?: string
        storePath?: string
        params?: T
    }

    interface ElValuePayload<T = any> {
        loc: keyof T
        el?: T[keyof T]
        value?: T[keyof T]
    }

    interface FetchItemsParams {
        endpoint: string
        storePath: string
        params: Record<string, any>
    }

    interface Item {
        id?: number
        [key: string]: any
    }

    // Define Material, Service, Product, Payment, TaxDeduction, Expense based on the usage in order-mutations.ts
    interface Material extends Item {
        id: number
        price: number | string
    }

    interface Service extends Item {
        id: number
        price: number | string
    }

    interface Product extends Item {
        id: number | null
        discountedPrice: number | string | null
    }

    interface Payment extends Item {
        id: number
        amount: number | string
    }

    interface TaxDeduction extends Item {
        id: number
        amount: number
    }

    interface Expense extends Item {
        id: number
        category?: string
    }

    interface OrderState {
        order_materials?: Material[]
        order_services?: Service[]
        products?: Product[]
        discount?: number
        total?: number
        grand_total?: number
        vat?: number
        tax_deductions?: TaxDeduction[]
        payments?: Payment[]
        tax_deduction?: TaxDeduction
        expense?: Expense
    }

    interface GenericOrderPayload<T, K extends keyof T> {
        loc: K
        el: T[K] extends Array<infer U> ? U : never
        value?: T[K]
    }

    interface Modal {
        shown: boolean
        message: string
        action: () => void
    }

    interface ModalState {
        message: string | null
        display: boolean
    }

    interface NotificationState {
        message: string | null
        type: string | null
        error: string | null
        errors: string[]
        style: string | null
        display: boolean
    }

    interface PaginationState {
        current_page: number
        last_page: number
        first_page_url: string
        last_page_url: string
        next_page_url: string
        prev_page_url: string
        per_page: number
        from: number
        to: number
        total: number
        [key: string]: any
    }

    interface Role extends Model {
        name: string
        label: string | null
    }

    interface RootState {
        auth: AuthState
        notification: NotificationState
        modal: ModalState
        pagination: PaginationState
    }

    interface SearchParams {
        query: string
        page: number
        perPage: number
        indexName: string
        filter?: string
    }

    interface SearchResult<T = any> {
        current_page: number
        per_page: number
        last_page: number
        total: number
        data: T[]
    }

    interface StatsState {
        data: Order[]
        totals: {
            potentiallyDueBalance: number | null
            dueBalance: number | null
        }
    }

    interface UpdateItemParams {
        item: Item
        itemClone: Item
        routeName: string
    }

    interface StatsTotals {
        materialsTotal: any
        servicesTotal: any
        grandTotal: any
        vatGrandTotal: any
        expenses: any
        paymentsTotal: any
        productsSold: any
        productsTotal: any
        aveUnitPrice: number
    }

    interface StoreItem {
        type: string
        format?: (item: any) => string | null
        compute?: (item: any) => any
        disabled?: boolean
        cast?: string
        label?: string
        class?: string
        alert?: boolean
    }

    interface StoreUpdater {
        setter: string
        parent: string
        items: Record<string, StoreItem>
    }

    interface GenericPayload<T, K extends keyof T> {
        loc: K
        value: T[K]
    }

    interface Order extends Item {
    }

}

export { }
