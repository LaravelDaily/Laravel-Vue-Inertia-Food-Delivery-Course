<script setup>
import { useForm } from '@inertiajs/vue3'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const form = useForm({
  name: '',
  email: ''
})

const addMember = () => {
  form.post(route('vendor.staff-members.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset()
  })
}
</script>

<template>
  <section class="max-w-xl">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Add Restaurant Staff Member</h2>

      <p class="mt-1 text-sm text-gray-600"></p>
    </header>

    <form @submit.prevent="addMember" class="mt-6 space-y-6">
      <div class="form-group">
        <InputLabel for="name" value="Name" />

        <TextInput id="name" v-model="form.name" type="text" :disabled="form.processing" />

        <InputError :message="form.errors.name" />
      </div>

      <div class="form-group">
        <InputLabel for="email" value="Email" />

        <TextInput
          id="email"
          v-model="form.email"
          type="email"
          autocomplete="email"
          :disabled="form.processing"
        />

        <InputError :message="form.errors.email" />
      </div>

      <div class="flex items-center gap-4">
        <PrimaryButton :disabled="form.processing">Add</PrimaryButton>

        <Transition
          enter-from-class="opacity-0"
          leave-to-class="opacity-0"
          class="transition ease-in-out"
        >
          <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Added.</p>
        </Transition>
      </div>
    </form>
  </section>
</template>
