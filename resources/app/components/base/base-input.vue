<template>
  <div class="input-wrap">
    <label v-if="labelOn" :for="label" class="input-label">{{ formatLabel(label) }}:</label>
    <input v-if="edit"
           :id="label"
           type="text"
           class="input-field"
           :placeholder="formatLabel(label)"
           :title="label"
           :value="modelValue"
           @input="$emit('update:modelValue', $event.target.value)">
    <div v-if="!edit" class="input-field">{{ modelValue }}</div>
  </div>
</template>

<script>
import { defineComponent } from 'vue';

import { startCase } from 'lodash-es'

export default defineComponent({
  emits: ['update:modelValue'],

  props: {
    modelValue: null,
    edit: { type: Boolean },
    labelOn: { type: Boolean, default: true },
    label: { type: String },
  },

  methods: {
    formatLabel(label) {
      return label ? startCase(label) : ''
    }
  },
});
</script>
