import {
    camelCase,
    isEmpty,
    isEqual,
    isObject,
    startCase,
    upperFirst
} from 'lodash-es'
import { format } from 'date-fns'
import pluralize from 'pluralize'

export function notificationStyle(code: string): string | null {
    const opt = Number(String(code).charAt(0))
    if (opt === 1) return 'info'
    if (opt === 2) return 'success'
    if (opt === 3) return 'redirection'
    if (opt === 4) return 'client-error'
    if (opt === 5) return 'server-error'
    return null
}


export function fixedNum(value: any) {
    return (value === null || value === undefined)
        ? null
        : Number(value).toFixed(2)
}
export const currency = (value: any) =>
    new Intl.NumberFormat("en-GB", { style: "currency", currency: "GBP" }).format(value || 0)

export function dateFormat(date: string, options: string = 'ccc dd MMM yyyy') {
    return date ? format(Date.parse(date), options) : date
}
export function extractIds(ids: string[], container: any) {
    if (!isObject(container)) console.error('Argument container must be an object')
    for (const id of ids) {
        if (container.hasOwnProperty(id)) {
            container[id + '_id'] = container[id].id
            delete container[id]
        }
    }
}
type Child = Record<string, any>
type Children = Record<string, Child>
export const flattenChildren = (children: Children): Child =>
    Object.values(children).reduce((acc, child) => ({ ...acc, ...child }), {})
export const extractDiff = (src: any, target: any) => {
    const diff = Object.keys({ ...src, ...target }).reduce((acc, key) => {
        if (!isEqual(src[key], target[key])) acc[key] = target[key]
        return acc
    }, {} as any)
    return isEmpty(diff) ? null : diff
}

export const aggregateData = (data: any, interval: number) => {
    // Sort by date
    const sorted = [...data].sort((a, b) => a.date - b.date)
    const labels = []
    const ordersArray = []
    const materialsArray = []
    const expensesArray = []

    for (let i = 0; i < sorted.length; i += interval) {
        const chunk = sorted.slice(i, i + interval)
        if (chunk.length > 0) {
            // Compute averages
            const ordersSum = chunk.reduce((acc, cur) => acc + cur.orders, 0)
            const materialsSum = chunk.reduce((acc, cur) => acc + cur.materials, 0)
            const expensesSum = chunk.reduce((acc, cur) => acc + cur.expenses, 0)

            const ordersAvg = ordersSum / chunk.length
            const materialsAvg = materialsSum / chunk.length
            const expensesAvg = expensesSum / chunk.length

            // Label = start day of the interval
            const labelDate = chunk[0].date.toISOString().split('T')[0]
            labels.push(labelDate)
            ordersArray.push(ordersAvg)
            materialsArray.push(materialsAvg)
            expensesArray.push(expensesAvg)
        }
    }
    return { labels, ordersArray, materialsArray, expensesArray }
}


export const getAggregatedItem = (item: any, mapValues: { [key: string]: string[] }) => {

    return Object.fromEntries(
        Object.entries(mapValues).map(([newProp, propsToCombine]) => [
            newProp,
            propsToCombine
                .map((prop) =>
                    prop.includes('.')
                        ? prop.split('.').reduce((obj, key) => obj?.[key], item)
                        : item[prop]
                )
                .filter(Boolean)
                .join(" "),
        ])
    )
}
export function isEmptyObj(obj: Record<string, any>, skip: string[] = []): boolean {
    const notEmpty: string[] = []
    for (const key in obj) {
        if (skip.includes(key)) continue
        if (isObject(obj[key]) && !isEmptyObj(obj[key], skip)) notEmpty.push(key)
        if (
            (!isEmpty(obj[key]) && !isObject(obj[key])) ||
            typeof obj[key] === 'number' ||
            typeof obj[key] === 'boolean'
        ) {
            notEmpty.push(key)
        }
    }
    return notEmpty.length === 0
}


export function joinPropValue(obj: any, props: any, divider: string = ' ') {
    const arr = []
    for (const prop in obj) {
        if (props.includes(prop) && !!obj[prop]) {
            arr.push(obj[prop])
        }
    }
    return arr.join(divider) || 'TBC'
}

export const prepareChartData = (options: Omit<DataSet, "data">[], stats: any) => {
    const labels: any[] = []
    const datasets: any[] = []

    for (let index in options) {
        const option = options[index]

        const dataset: DataSet = {
            label: startCase(option.label),
            data: [],
            backgroundColor: option.backgroundColor,
            borderColor: option.borderColor,
        }

        for (let date in stats) {
            if (!labels.includes(date)) {
                labels.unshift(date)
            }
            const value = stats.value[date][option.label]
            dataset.data.unshift(value)
        }

        datasets.push(dataset)
    }

    return { labels, datasets }
}

export function setDescendantProp(obj: any, desc: string, value: any) {
    const arr = desc.split('.')
    while (arr.length > 1) {
        obj = obj[arr.shift() as string]
    }
    obj[arr[0]] = value
}
export function validate(object: any, rules: any) {
    const failedRules = []
    for (const rule of rules) {
        const value = object[rule]
        if (value === null || value === '') {
            failedRules.push(rule)
        }
        if (value !== null && value !== '' && typeof value === 'object' && isEmpty(value)) {
            failedRules.push(rule)
        }
    }
    return failedRules
}

export const filters: { [key: string]: any } = {
    currency,
    startCase,
    dateFormat,
    fixedNum,
    percentage: (value: string) => `${ value }%`,
    raw: (data: any) => data
}

export default {
    install(app: any) {
        // Expose filters as global properties
        app.config.globalProperties.$filters = filters
        // Expose methods globally
        app.config.globalProperties.$helpers = {
            joinPropValue,
            currency,
            validate,
            extractDiff,
            pluralize,
            camelCase,
            upperFirst,
            isEmpty,
            isEmptyObj,
            isObject,
            extractIds
        }
    }
}
