<script setup>
import { onMounted, ref } from 'vue'

defineProps({
  modelValue: {
    type: String,
    required: true
  },
  options: {
    type: Array,
    required: true
  },
  optionValue: {
    type: String,
    required: true
  },
  optionLabel: {
    type: String,
    required: true
  },
  defaultOption: {
    type: Object,
    required: false
  }
})

defineEmits(['update:modelValue'])

const input = ref(null)

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

defineExpose({ focus: () => input.value.focus() })
</script>

<template>
  <select
    class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
    :value="modelValue"
    @change="$emit('update:modelValue', $event.target.value)"
    ref="input"
  >
    <option v-if="defaultOption" disabled hidden :value="defaultOption[optionValue]">
      {{ defaultOption[optionLabel] }}
    </option>
    <option v-for="option in options" :key="option[optionValue]" :value="option[optionValue]">
      {{ option[optionLabel] }}
    </option>
  </select>
</template>
