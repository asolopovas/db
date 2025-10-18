import { updatePrice } from '../order-helpers'

function setMaterialItem<K extends keyof OrderMaterialModel>(
    state: OrderState,
    { loc, value }: GenericPayload<OrderMaterialModel, K>
): void {
    const material = state.order_material
    if (!material) return
    material[loc] = value

    if (loc === "material" && typeof value === 'object' && value !== null && 'price' in value) {
        material.unit_price = value.price
    }

    if (material.id !== 0) {
        updatePrice(material)
    }
}
export { setMaterialItem }
