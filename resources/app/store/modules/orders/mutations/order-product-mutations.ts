import { updateOrderPrice } from './order-mutations'
import {
    updateProductPrice,
    updateProductUnitPrice,
    updateProductWastage,
} from '../order-helpers'
import { cloneDeep, findIndex } from 'lodash-es'

export function editProduct(state: OrderState, id: number): void {
    const product = state.products?.find((item) => item.id === id)
    if (product) {
        state.product = cloneDeep(product)
    }
}

export function deleteProduct(state: OrderState, product: Product): void {
    const index = state.products?.indexOf(product)
    if (state.products && index) {
        state.products.splice(index, 1)
        updateOrderPrice(state)
    }
}

export function saveProduct(state: OrderState, product: Product): void {
    const index = findIndex(state.products, { id: product.id })
    if (state.products && index !== -1) {
        state.products[index] = product
        updateOrderPrice(state)
    }
}

export function resetProduct(state: any): void {
    state.product = {
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
    }
}

export function updateProductMeterage(state: OrderState, id: number): void {
    const product: Product | undefined = state.products?.find((product) => product.id === id)
    if (!product) return

    const meterage = product.areas.reduce((sum: number, area: ProductArea) => sum + Number(area.meterage || 0), 0)
    const wastage = (meterage * (product.wastage_rate || 0)) / 100

    product.meterage = meterage
    product.wastage = wastage

    updateProductPrice(product)
    updateOrderPrice(state)
}

export function setProductComponent<K extends keyof Product>(
    state: OrderState,
    { loc, value }: GenericPayload<Product, K>
): void {
    if (!state.product) return
    state.product[loc] = value

    if (loc !== 'unit_price' && loc !== 'meterage' && loc !== 'wastage_rate') {
        updateProductUnitPrice(state.product)
    } else {
        updateProductWastage(state.product)
    }

    updateProductPrice(state.product)
}
