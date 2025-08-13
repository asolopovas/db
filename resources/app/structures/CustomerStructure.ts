import orderIcon from "@icons/order.svg?raw"
import editIcon from "@icons/edit.svg?raw"
import addIcon from "@icons/add.svg?raw"
import saveIcon from "@icons/save.svg?raw"
import cancelIcon from "@icons/save.svg?raw"

export default {
    className: "w-[500px] max-w-full",
    tabs: { general: true, address: false, extras: false },
    sort: {
        address: 'postcode',
        name: 'lastname',
    },
    columns: {
        id: ["id"],
        name: ["title", "firstname", "lastname"],
        address: ["address_line_1", "town", "county", "city"],
        postcode: ["postcode"],
    },
    groups: [
        {
            name: "general",
            className: "grid md:grid-cols-2 gap-3 w-full",
            id: { type: "number", edit: false, hide: true },
            title: {
                type: "select",
                options: ["Mr.", "Mrs.", "Miss", "Dr."],
            },
            firstname: { type: "text", className: 'w-[140px]' },
            lastname: { type: "text" },
            phone: { type: "tel", autocomplete: "off" },
            mobile_phone: { type: "tel" },
            home_phone: { type: "tel" },
            work_phone: { type: "tel" },
            email1: { type: "email" },
            email2: { type: "email" },
            email3: { type: "email" }
        },
        {
            name: "address",
            className: "grid grid-cols-2 gap-2",
            address_line_1: { type: "text" },
            address_line_2: { type: "text" },
            region: { type: "text" },
            town: { type: "text" },
            county: { type: "text" },
            city: { type: "text" },
            postcode: { type: "text" },
            country: { type: "text", default: "United Kingdom" },
            fax: { type: "tel" },
        },
        {
            name: "extras",
            className: "",
            company: { type: "text" },
            web_page: { type: "text" },
            status: { type: "select", options: ["ACTIVE", "ARCHIVED"] },
            note: { type: "textarea", height: "140px" },
        },
        {
            name: "timestamps",
            className: "",
            created_at: { type: "timestamp", readonly: true },
            updated_at: { type: "timestamp", readonly: true }
        }
    ],
    buttonsWrapper: "flex justify-end gap-2",
    buttons: {
        view: [
            {
                class: "btn-action bg-emerald-600 hover:bg-emerald-700 text-white",
                label: "Edit",
                action: "edit",
                icon: editIcon
            },
            {
                class: "btn-action bg-blue-500 text-white hover:bg-blue-400",
                label: "Orders",
                action: "viewOrders",
                icon: orderIcon
            },
        ],
        edit: [
            {
                class: "btn-action bg-blue-500 text-white hover:bg-blue-400",
                label: "Orders",
                action: "viewOrders",
                icon: orderIcon
            },
            {
                class: "btn-action bg-emerald-600 hover:bg-emerald-700 text-white",
                label: "New Order",
                action: "newOrder",
                icon: addIcon
            },
            {
                class: "btn-action bg-yellow-500  text-gray-800 hover:bg-yellow-400 hover:text-gray-700",
                label: "Save",
                action: "save",
                icon: saveIcon
            },
        ],
        add: [
            {
                class: "btn-action bg-emerald-600 hover:bg-emerald-700 text-white",
                label: "Add",
                action: "add",
                icon: addIcon
            },
            {
                class: "btn-action bg-rose-500 hover:bg-rose-600 text-white",
                label: "Cancel",
                action: "cancel",
                icon: cancelIcon
            },
        ]
    }

}
