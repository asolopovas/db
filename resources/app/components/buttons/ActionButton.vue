<template>
    <base-button type="button" :class=" button.class " @click="$emit(button.emit)">
        <i v-if="button.icon" :class=" button.icon " />{{ $filters.startCase(button.text) }}
        <slot />
    </base-button>
</template>
<script>
import {defineComponent} from 'vue'

export default defineComponent({

    props: ['button'],
})
</script>
