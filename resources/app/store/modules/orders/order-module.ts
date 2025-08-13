import * as Actions from "@store/modules/orders/actions/order-actions"
import * as ProductActions from "@store/modules/orders/actions/order-product-actions"
import * as ProductAreaActions from "@store/modules/orders/actions/order-product-areas-actions"
import * as Mutations from "@store/modules/orders/mutations/order-mutations"
import * as ProductMutations from "@store/modules/orders/mutations/order-product-mutations"
import * as ProductAreaMutations from "@store/modules/orders/mutations/order-product-area-mutations"
import * as MaterialMutations from "@store/modules/orders/mutations/order-material-mutations"
import * as ServiceMutations from "@store/modules/orders/mutations/order-service-mutations"
import * as PaymentMutations from "@store/modules/orders/mutations/order-payment-mutations"
import { currency } from "@lib/global-helpers"
import type { GetterTree, Module } from "vuex"
const state: OrderState = {
    created_at: undefined,
    id: undefined,
    balance: undefined,
    cc: undefined,
    city: undefined,
    company: undefined,
    company_id: undefined,
    county: undefined,
    country: undefined,
    customer: undefined,
    customer_id: undefined,
    date_due: undefined,
    discount: undefined,
    due: undefined,
    due_amount: undefined,
    expenses: [],
    expense: {
        id: 0,
        order_id: 0,
        category: "",
        amount: "",
        details: "",
        date: "",
    },
    grand_total: undefined,
    mail_message: undefined,
    notes: undefined,
    order_material: {
        id: 0,
        order_id: 0,
        material_id: 0,
        material: {
            id: 0,
            name: "",
            price: "",
            measurement_unit: ""
        },
        unit_price: 0,
        quantity: 0,
    },
    order_materials: [],
    order_service: {
        id: 0,
        order_id: 0,
        service_id: 0,
        service: {
            id: 0,
            name: "",
            price: "",
            measurement_unit: ""
        },
        quantity: "",
        unit_price: "",
    },
    order_services: [],
    overdue: "",
    overdueBy: "",
    paid: "",
    payment: {
        id: 0,
        order_id: 0,
        description: "",
        amount: "",
        date: "",
    },
    payment_terms: undefined,
    payments: [],
    postcode: undefined,
    product: {
        areas: null,
        dimension: null,
        dimension_id: null,
        discount: 0,
        discountedPrice: null,
        extra: null,
        extra_id: null,
        floor: null,
        floor_id: null,
        grade: null,
        grade_id: null,
        id: null,
        meterage: null,
        name: null,
        order_id: null,
        price: null,
        unit_price: null,
        wastage: null,
        wastage_rate: 10,
    },
    product_area: {
        currentProduct: undefined,
        id: undefined,
        el: undefined,
        meterage: undefined,
        name: undefined,
        product: undefined,
        product_id: undefined,
    },
    products: [],
    proforma: false,
    proforma_breakdown: false,
    proforma_message: undefined,
    project_id: undefined,
    project: {
        street: undefined,
        town: undefined,
        county: undefined,
        city: undefined,
        postcode: undefined,
        country: undefined,
    },
    reverse_charge: false,
    sent: false,
    status: undefined,
    status_id: undefined,
    street: undefined,
    tax_deduction: {
        id: 0,
        amount: 0,
        ref: undefined,
        date: "",
    },
    tax_deductions: [],
    total: undefined,
    town: undefined,
    user_id: undefined,
    updated_at: undefined,
    vat: 20,
    vat_total: undefined,
}



const getters: GetterTree<OrderState, RootState> = {
    productsView: (state) => {
        if (!state.products) return
        return state.products.map((product: Nullable<Product>) => {
            const { floor, grade, extra } = product
            const dimension =
                !product.dimension?.length
                    ? `${ product.dimension?.thickness }/${ product.dimension?.width }mm`
                    : `${ product.dimension?.thickness }/${ product.dimension?.width }/${ product.dimension.length }mm`
            return {
                id: product.id,
                el: product,
                name: floor?.name,
                specs: `${ grade?.name }, ${ dimension }, ${ currency(product.unit_price) }/m², ${ extra?.name }`,
                discount: `${ product.discount || 0 }%`,
                wastage: `${ product.wastage || 0 }m² @ ${ product.wastage_rate || 0 }%`,
                meterage: `${ product.meterage || 0 }m²`,
                price: currency(product.price),
                discountedPrice: currency(product.discountedPrice),
            }
        })
    },
    productsAreas: (state: OrderState): ProductAreaGetter[] => {
        if (!state.products) return []
        const products = state.products
            .filter((product) => product.areas.length > 0)
            .map((product) => {
                const areas = (product.areas as ProductAreaGetter[]).map((productArea) => ({
                    el: productArea,
                    id: productArea.id,
                    name: productArea.area.name,
                    meterage: productArea.meterage,
                })) as ProductArea[]

                const dimension: DimensionModel = {
                    type: product.dimension.type,
                    price: product.dimension.price,
                    thickness: product.dimension.thickness,
                    width: product.dimension.width,
                    length: product.dimension.length,
                }
                const specs = `${ dimension.length }x${ dimension.width }x${ dimension.thickness }mm`

                return {
                    product,
                    el: product,
                    id: product.id,
                    name: product.floor.name,
                    meterage: product.meterage,
                    dimension,
                    type: dimension.type,
                    specs,
                    areas,
                }
            }) as ProductAreaGetter[]

        return products
    }
}

const ordersModule: Module<OrderState, RootState> = {
    state,
    actions: { ...Actions, ...ProductActions, ...ProductAreaActions },
    mutations: {
        ...Mutations,
        ...ProductMutations,
        ...ProductAreaMutations,
        ...MaterialMutations,
        ...ServiceMutations,
        ...PaymentMutations,
    },
    getters,
}

export default ordersModule
