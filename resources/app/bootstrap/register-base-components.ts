import { upperFirst, camelCase } from 'lodash-es'
import type { App, Component } from 'vue'

const components = import.meta.glob<{ default: Component }>('@components/base/*', { eager: true })

export default (app: App) => {
    Object.entries(components).forEach(([path, definition]) => {
        const fileName = path.split('/').pop() || ''
        const componentName = upperFirst(camelCase(fileName.replace(/\.\w+$/, '')))
        app.component(componentName, (definition as { default: Component }).default || definition)
    })
}
