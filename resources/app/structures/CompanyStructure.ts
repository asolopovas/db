export default {
    height: "390px",
    className: "grid md:grid-cols-2 gap-2",
    columns: {
        id: ["id"],
        name: ["name"],
        vat_number: ["vat_number"],
        bank: ["bank"],
        account_number: ["account_nr"],
        sort_code: ["sort_code"],
    },
    fields: {
        name: {type: 'text'},
        telephone1: {type: 'tel', hide: true},
        telephone2: {type: 'tel', hide: true},
        web: {type: 'text', hide: true},
        email: {type: 'email'},
        vat_number: {type: 'text'},
        bank: {type: 'text'},
        sort_code: {type: 'text'},
        account_nr: {type: 'text'},
        address: {type: 'textarea', height: '100px'},
        notes: {type: 'textarea'},
    }
}
