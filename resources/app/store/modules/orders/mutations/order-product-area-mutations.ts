import { cloneDeep } from 'lodash-es'
import { updateProductMeterage } from './order-product-mutations'

function getProduct(id: number, state: OrderState): Product | undefined {
    return state.products?.find((product) => product.id === id)
}

export function addProductArea(state: OrderState, area: ProductArea): void {
    if (!area.product_id) return
    const product = getProduct(area.product_id, state)
    if (!product) return

    const index = state.products?.indexOf(product)

    if (state.products && index !== undefined && index >= 0) {
        product.areas = [area, ...product.areas]
        state.products[index] = { ...product }
    }
}
export function removeProductArea(
    state: OrderState,
    { area, product }: { area: AreaModel; product: Product }
): void {
    const prod = getProduct(product.id, state)
    if (!prod) return
    const index = state.products?.indexOf(prod)

    if (state.products && index !== undefined && index >= 0) {
        prod.areas = prod.areas.filter((item: ProductArea) => item.id !== area.id)
        state.products[index] = { ...prod }
    }
}
export function editProductArea(
    state: OrderState,
    { area, product }: { area: ProductArea; product: Product }
): void {
    const prod = getProduct(product.id, state)
    if (!prod) return
    const prodArea = prod.areas.find((item: ProductArea) => item.id === area.id)
    if (!prodArea) return

    if (state.product_area) {
        state.product_area.area = prodArea.area
        state.product_area.id = prodArea.id
        state.product_area.product = prod
        state.product_area.currentProduct = prod
        state.product_area.meterage = area.meterage
    }
}

export function saveProductArea(state: OrderState): void {
    if (!state.product_area) return

    const product = getProduct(state.product_area.product?.id || 0, state)
    const currentProduct = state.product_area.currentProduct

    if (!product || !currentProduct) return

    if (product.id !== currentProduct.id) {
        const area = currentProduct.areas.find(
            (area: ProductArea) => area.id === state.product_area?.id
        )
        if (!area) return

        const areaIndex = currentProduct.areas.indexOf(area)
        currentProduct.areas.splice(areaIndex, 1)
        product.areas.push(area)
        updateProductMeterage(state, currentProduct.id)
    } else {
        const index = state.products?.indexOf(product)
        if (state.products && index) {
            const area = product.areas.find(
                (area: ProductArea) => area.id === state.product_area?.id
            )
            if (!area) return

            const areaIndex = product.areas.indexOf(area)
            product.areas[areaIndex] = cloneDeep(state.product_area)
            state.products[index] = product
        }
    }
}

export function setProductAreaComponent<K extends keyof ProductArea>(
    state: OrderState,
    { loc, value }: GenericPayload<ProductArea, K>
): void {
    if (state.product_area) {
        state.product_area[loc] = value
    }
}
