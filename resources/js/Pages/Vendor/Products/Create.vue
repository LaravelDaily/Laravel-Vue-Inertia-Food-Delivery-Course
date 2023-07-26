<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
const props = defineProps({
  categories: {
    type: Array
  },
  category_id: {
    type: String
  }
})
const form = useForm({
  category_id: props.category_id,
  name: '',
  price: ''
})
const submit = () => {
  form.post(route('vendor.products.store'))
}
</script>

<template>
  <Head title="Add New Product" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Add New Product</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 overflow-x-scroll">
            <div class="p-6 text-gray-900 overflow-x-scroll">
              <form @submit.prevent="submit" class="flex flex-col gap-4">
                <div class="form-group">
                  <InputLabel for="category_id" value="Category" />
                  <SelectInput
                    id="category"
                    v-model="form.category_id"
                    :options="categories"
                    option-value="id"
                    option-label="name"
                    :default-option="{
                      id: '',
                      name: 'Product Category'
                    }"
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.category_id" />
                </div>

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

                <div class="form-group">
                  <InputLabel for="price" value="Price" />
                  <TextInput
                    id="name"
                    type="number"
                    step="0.01"
                    v-model="form.price"
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.price" />
                </div>

                <div>
                  <PrimaryButton :processing="form.processing"> Create New Product </PrimaryButton>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
