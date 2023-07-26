<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
const props = defineProps({
  category: {
    type: Object
  }
})
const form = useForm({
  name: props.category.name
})
const submit = () => {
  form.patch(route('vendor.categories.update', props.category))
}
</script>

<template>
  <Head :title="'Edit ' + category.name" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ 'Edit ' + category.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 overflow-x-scroll">
            <div class="p-6 text-gray-900 overflow-x-scroll">
              <form @submit.prevent="submit" class="flex flex-col gap-4">
                <div class="form-group">
                  <InputLabel for="name" value="Name" />
                  <TextInput
                    id="name"
                    type="text"
                    v-model="form.name"
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.name" />
                </div>

                <div>
                  <PrimaryButton :processing="form.processing">
                    Update Product Category
                  </PrimaryButton>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
