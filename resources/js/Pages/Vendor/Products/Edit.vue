<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import SelectInput from '@/Components/SelectInput.vue'
const props = defineProps({
  product: {
    type: Object
  },
  categories: {
    type: Array
  }
})
const form = useForm({
  category_id: props.product.category_id,
  name: props.product.name,
  price: (props.product.price / 100).toFixed(2)
})
const submit = () => {
  form.patch(route('vendor.products.update', props.product))
}
</script>

<template>
  <Head :title="'Edit ' + product.name" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ 'Edit ' + product.name }}
      </h2>
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
                  <PrimaryButton :processing="form.processing"> Update Product </PrimaryButton>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
