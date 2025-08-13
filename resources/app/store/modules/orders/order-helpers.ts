export function updatePrice(item: Item): void {
    item.price = sum([item])
}

export function sum(items: Item[], field: string = 'price'): number {
    return items.length
        ? items.reduce((sum, item) => Number((sum + (item[field] || 0)).toFixed(2)), 0)
        : 0
}

export function updateProductWastage(product: Product): void {
    if (!product.wastage_rate || !product.meterage) return

    const wastageRate = product.wastage_rate / 100
    product.wastage = product.meterage * wastageRate
}

export function updateProductUnitPrice(product: Product): void {
    product.unit_price = 0
    for (const spec in product) {
        const field = product[spec]
        if (field !== null && typeof field === 'object' && 'price' in field && typeof field.price === 'number') {
            product.unit_price += field.price
        }
    }
}
export function updateProductPrice(product: Product): void {
    if (!product.unit_price) return

    const price = ((Number(product.meterage) + Number(product.wastage)) * product.unit_price).toFixed(2)
    product.price = Number(price)
    product.discountedPrice = product.price * (1 - (product.discount || 0) / 100)
}
