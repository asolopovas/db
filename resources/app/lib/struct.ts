import CompanyStructure from '@app/structures/CompanyStructure'
import ContactStructure from '@app/structures/ContactStructure'
import CustomerStructure from '@app/structures/CustomerStructure'
import DimensionStructure from '@app/structures/DimensionStructure'
import NotesStructure from '@app/structures/NotesStructure'
import ProjectStructure from '@app/structures/ProjectStructure'
import TwoColStructure from '@app/structures/TwoColStructure'
import SettingsStructure from '@app/structures/SettingsStructure'
import UserStructure from '@app/structures/UserStructure'
import FloorsStructure from '../structures/FloorsStructure'

const defaultStruct = {
    fields: {
        name: {
            type: 'text'
        }
    }
}
const structs: { [key: string]: any } = {
    areas: { ...defaultStruct },
    companies: CompanyStructure,
    contacts: ContactStructure,
    customers: CustomerStructure,
    dimensions: DimensionStructure,
    extras: TwoColStructure,
    floors: FloorsStructure,
    grades: TwoColStructure,
    materials: {
        ...TwoColStructure,
        measurement_unit: { type: 'text' },
    },
    notes: NotesStructure,
    projects: ProjectStructure,
    services: {
        ...TwoColStructure,
        measurement_unit: { type: 'text' },
    },
    settings: SettingsStructure,
    statuses: { ...defaultStruct },
    users: UserStructure,
}
export default (name: string) => {
    const struct = structs[name] || defaultStruct

    return struct
}
