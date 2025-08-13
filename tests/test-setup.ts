import { vi } from 'vitest'
import { createCanvas } from 'canvas'

// Mock `HTMLCanvasElement.getContext` for Chart.js
global.HTMLCanvasElement.prototype.getContext = vi.fn((type) => {
    if (type === '2d') {
        return createCanvas(200, 200).getContext('2d')
    }
    return null
})

// Mock `vue-chartjs` to prevent rendering issues in tests
vi.mock('vue-chartjs', () => ({
    LineChart: {
        template: '<div class="line-chart-mock"></div>'
    }
}))

// Mock `chartjs-plugin-datalabels`
vi.mock('chartjs-plugin-datalabels', () => ({
    default: {}
}))

// Mock the Modal component to avoid full rendering
vi.mock('@components/Modal.vue', () => ({
    default: {
        template: `
            <div>
                <slot name="title"></slot>
                <slot></slot>
            </div>
        `
    }
}))
