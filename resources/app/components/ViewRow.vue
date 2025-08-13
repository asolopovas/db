<template>
    <div>
        <div class="label">{{ $filters.startCase(label) }}:</div>
        <div class="cell value" :class=" label + '-field' "
            v-text=" label === 'price' ? parseFloat(value).toLocaleString('en-GB', { style: 'currency', currency: 'GBP' }) : value ">
        </div>
    </div>
</template>
<script>
import {defineComponent} from 'vue'

export default defineComponent({
    props: ['label', 'value', 'params'],
})
</script>
