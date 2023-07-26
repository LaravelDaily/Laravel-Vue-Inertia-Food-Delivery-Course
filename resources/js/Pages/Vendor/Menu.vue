<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps({
  categories: {
    type: Array
  }
})
</script>

<template>
  <Head title="Restaurant Menu" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Restaurant Menu</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6" v-if="can('category.create')">
            <Link class="btn btn-primary" :href="route('vendor.categories.create')">
              Add New Product Category
            </Link>
          </div>
          <div class="p-6 text-gray-900 overflow-x-scroll flex flex-col gap-8">
            <div v-for="category in categories" :key="category.id" class="flex flex-col gap-4">
              <div class="flex justify-between">
                <div class="">
                  <div class="text-2xl font-bold">{{ category.name }}</div>
                </div>
                <div class="flex gap-4 items-center">
                  <Link
                    :href="route('vendor.categories.edit', category)"
                    class="btn btn-secondary btn-sm"
                  >
                    Edit
                  </Link>
                  <Link
                    :href="route('vendor.categories.destroy', category)"
                    class="btn btn-danger btn-sm"
                    method="delete"
                    as="button"
                  >
                    Delete
                  </Link>
                </div>
              </div>
              <div>
                <Link
                  class="btn btn-secondary btn-sm"
                  :href="route('vendor.products.create', { category_id: category.id })"
                >
                  Add Product to {{ category.name }}
                </Link>
              </div>
              <div class="flex flex-col gap-6">
                <div
                  v-for="product in category.products"
                  :key="product.id"
                  class="flex items-center justify-between pb-6 border-b gap-4"
                >
                  <div class="flex flex-col">
                    <div class="font-bold">{{ product.name }}</div>
                    <div class="">{{ (product.price / 100).toFixed(2) }} &euro;</div>
                  </div>
                  <div class="flex gap-4">
                    <Link
                      :href="route('vendor.products.edit', product)"
                      class="btn btn-secondary btn-sm"
                    >
                      Edit
                    </Link>
                    <Link
                      :href="route('vendor.products.destroy', product)"
                      class="btn btn-danger btn-sm"
                      method="delete"
                      as="button"
                    >
                      Delete
                    </Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
