
declare global {
    type CustomerType = Address & ContactInfo & {
        title: string
        firstname: string
        lastname: string
        note?: string
        web_page?: string
        company?: string
    }

    interface ItemsState {
        [key: string]: ItemsStoreModule
    }

    type NamePrice = {
        name: string
        price: number
    }
    interface AreaModel extends Model {
        name: string
    }

    interface CompanyModel extends Model {
        name: string
        address: string
        telephone1: string
        telephone2: string
        web: string
        email: string
        vat_number: string
        bank: string
        sort_code: string
        account_nr: string
        iban: string
        swift: string
        disclaimer: string
        notes: string // Long text
    }

    interface CustomerModel extends Model {
        title: string
        firstname: string
        lastname: string
        company: string
        address_line_1: string
        address_line_2: string
        town: string
        county: string
        city: string
        postcode: string
        country: string
        phone: string
        mobile_phone: string
        home_phone: string
        work_phone: string
        fax: string
        email1: string
        email2: string
        email3: string
        note: string
        web_page: string
        grand_design: boolean
    }

    interface DimensionModel extends Model {
        width: number
        length: number
        thickness: number
        type: string
        price: number
    }
    interface ExpenseModel extends Model {
        category: string
        order_id: number
        amount: number
        details: string
        date: string
    }

    interface MaterialModel extends Model {
        name: string
        price?: number
        measurement_unit?: string
    }
    interface OrderMaterialModel extends Model {
        order_id: number | string
        material_id: number | string
        material: Material
        unit_price: number | string
        quantity: number | string
    }
    interface OrderServiceModel extends Model {
        order_id: number
        service_id: number
        service: Service
        unit_price: number | string
        quantity: number | string
    }
    interface ProductModel extends Model {
        name: string
        order_id: number
        floor_id: number
        extra_id: number
        dimension_id: number
        grade_id: number
        discount: number
        floor: NamePrice
        grade: NamePrice
        dimension: Dimension
        extra: NamePrice
        discountedPrice: number
        wastage: number
        wastage_rate: number
        meterage: number
        unit_price: number
        price: number
        [key: string]: any
    }
    interface ProductAreaGetter extends Model {
        el: Product,
        area_id: number
        meterage: number
        name: string
        area: Area
        product: Product
        specs: string
        areas: ProductArea[]
        dimension: Dimension
        type: string
    }

    interface PaymentModel extends Model {
        order_id: number
        date: string
        amount: number | null
        description: string
    }
    interface ProductArea extends Model {
        id?: number
        el?: Area
        area?: Area
        currentProduct?: Product
        product?: Product
        product_id?: number,
        meterage?: number
        name?: string
    }

    interface ProjectModel extends Address, Model { }
    interface ServiceModel extends Model {
        name: string
        price: number
        measurement_unit: string
    }

    interface StatusModel extends Model {
        name: string
    }
    interface TaxDeduction {
        amount?: number
        ref?: string
        date?: string
    }
    interface OrderState extends Partial<Address>, Model {
        balance?: number
        cc?: string
        company?: Company
        company_id?: number
        country?: string
        customer?: Customer
        customer_id?: number
        date_due?: string
        discount?: number
        due?: number
        due_amount?: number
        expense?: Expense
        expenses?: Expense[]
        grand_total?: number
        mail_message?: string
        notes?: string
        order_material?: OrderMaterialModel
        order_materials?: MaterialModel[]
        order_service?: OrderServiceModel
        order_services?: OrderServiceModel[]
        overdue: boolean | ""
        overdueBy: number | ""
        paid: number | ""
        payment?: Payment
        payment_terms?: string
        payments?: Payment[]
        payments?: Payments
        product?: Product
        product_area?: ProductArea
        products?: Product[],
        proforma?: boolean
        proforma_breakdown?: boolean
        proforma_message?: string
        project?: Project
        project_id?: number
        reverse_charge?: boolean
        sent?: boolean
        status?: Status
        status_id?: number
        tax_deduction?: TaxDeduction
        tax_deductions?: TaxDeduction[]
        total?: number
        total?: number
        user_id?: number
        vat?: number
        vat_total?: number
        vat_total?: number
        [key: string]: any
    }
}

export { }
