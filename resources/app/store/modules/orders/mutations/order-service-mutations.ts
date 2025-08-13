import { updatePrice } from '../order-helpers'

function setServiceItem<K extends keyof OrderService>(
    state: OrderState,
    { loc, value }: GenericPayload<OrderService, K>
): void {
    const service = state.order_service
    if (!service) return

    service[loc] = value

    if (loc === "service" && typeof value === 'object' && value !== null && 'price' in value) {
        service.unit_price = value.price
    }

    if (service.id !== undefined) {
        updatePrice(service)
    }
}

export { setServiceItem }
