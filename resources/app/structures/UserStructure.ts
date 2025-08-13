export default {
    className: 'grid md:grid-cols-2 gap-3',
    columns: {
        id: ["id"],
        name: ["username"],
        role: ["role.name"],
    },
    fields: {
        name: { type: 'text' },
        username: { type: 'text' },
        title: { type: 'text' },
        email: { type: 'text' },
        role: { type: 'select', 'options': ['admin', 'user'] },
        phone: { type: 'text' },
        password: { type: 'password' },
        mobile: { type: 'text' },
        created_at: { type: 'text', hide: true },
        updated_at: { type: 'text', hide: true },
    },
}
