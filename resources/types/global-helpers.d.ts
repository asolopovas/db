import { startCase, currency, dateFormat, fixedNum, pluralize } from '@root/resources/app/lib/global-helpers' // Adjust import paths as needed

declare module '@vue/runtime-core' {


    // Declare global properties
    interface ComponentCustomProperties {
        $filters: {
            startCase: typeof startCase
            currency: typeof currency
            dateFormat: typeof dateFormat
            fixedNum: typeof fixedNum
            percentage: (value: string) => string
            raw: (data: any) => any
        }
        $helpers: {
            joinPropValue: typeof joinPropValue
            currency: typeof currency
            validate: typeof validate
            extractDiff: typeof extractDiff
            pluralize: typeof pluralize
            camelCase: typeof camelCase
            upperFirst: typeof upperFirst
            isEmpty: typeof isEmpty
            clone: typeof cloneDeep
            isEmptyObj: typeof isEmptyObj
            isObject: typeof isObject
            extractIds: typeof extractIds
        }
    }
}
