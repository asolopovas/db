export default {
    fields: {
        width: {type: 'text', format: ''},
        thickness: {type: 'number'},
        length: {type: 'number'},
        type: {
            type: 'select',
            options: ['Plank', 'Chevron', 'Herringbone', 'Versailles Panel', 'Mansion Weave'],
            filters: ['startCase']
        },
        price: {type: 'number'},
    }
}
