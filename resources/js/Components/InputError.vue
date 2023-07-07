<script setup>
defineProps({
  message: {
    type: String
  }
})
</script>

<template>
  <div v-show="message">
    <p class="text-sm text-danger-600">
      {{ message }}
    </p>
  </div>
</template>
