import { updatePrice } from '../order-helpers'

function setMaterialItem<K extends keyof OrderMaterialModel>(
    state: OrderState,
    { loc, value }: GenericPayload<OrderMaterialModel, K>
): void {
    const material = state.order_material
    if (!material) return
    console.log({ loc, value, material: { ...material } })


    material[loc] = value

    if (loc === "material" && typeof value === 'object' && value !== null && 'price' in value) {
        material.unit_price = value.price
    }

    console.log({ quantity: material.quantity, unit_price: material.unit_price })
    if (material.id !== 0) {

        updatePrice(material)
    }
}
export { setMaterialItem }
