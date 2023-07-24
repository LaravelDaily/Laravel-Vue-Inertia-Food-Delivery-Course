<script setup>
import { useForm } from '@inertiajs/vue3'
import DangerButton from '@/Components/DangerButton.vue'

defineProps({
  members: {
    type: Array,
    required: true
  }
})

const form = useForm({})

const removeMember = (member) => {
  form.delete(route('vendor.staff-members.destroy', member))
}
</script>

<template>
  <section class="max-w-xl">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Team Members</h2>

      <p class="mt-1 text-sm text-gray-600">All of the people that are part of this restaurant.</p>
    </header>
    <div class="mt-6 space-y-6">
      <div v-for="member in members" :key="member.id">
        <div class="flex items-center justify-between">
          <div>
            <div>{{ member.name }}</div>
            <div class="text-link">{{ member.email }}</div>
          </div>
          <div>
            <DangerButton
              v-if="can('user.delete')"
              @click="removeMember(member)"
              type="button"
              class="btn-sm"
            >
              Remove
            </DangerButton>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
