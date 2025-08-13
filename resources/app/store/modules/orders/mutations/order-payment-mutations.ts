

function setPaymentItem<K extends keyof Payment>(
    state: OrderState,
    { loc, value }: GenericPayload<Payment, K>
): void {
    if (!state.payment) return
    state.payment[loc] = value
}

export { setPaymentItem }
