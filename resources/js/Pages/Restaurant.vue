<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, usePage, useForm } from '@inertiajs/vue3'

const page = usePage()

const form = useForm({})

defineProps({
  restaurant: {
    type: Object
  }
})

const addProduct = (product) => {
  form.post(route('customer.cart.add', product), {
    preserveScroll: true,
    onError: () => {
      if (confirm(`This will remove your ${page.props.cart.restaurant_name} order.`)) {
        form.delete(route('customer.cart.destroy'), {
          onSuccess: () => addProduct(product)
        })
      }
    }
  })
}
</script>

<template>
  <Head :title="restaurant.name" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ restaurant.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 overflow-x-scroll flex flex-col gap-8">
            <div
              v-for="category in restaurant.categories"
              :key="category.id"
              class="flex flex-col gap-4"
            >
              <div class="flex justify-between">
                <div class="">
                  <div class="text-2xl font-bold">{{ category.name }}</div>
                </div>
              </div>
              <div class="flex flex-col gap-6">
                <div
                  v-for="product in category.products"
                  :key="product.id"
                  class="flex justify-between pb-6 border-b gap-4"
                >
                  <div class="grow flex flex-col gap-2">
                    <div class="font-bold">{{ product.name }}</div>
                    <div class="">{{ (product.price / 100).toFixed(2) }} &euro;</div>
                    <div class="grow flex items-end">
                      <button
                        v-if="can('cart.add') || !$page.props.auth.user"
                        @click="addProduct(product)"
                        class="btn btn-primary btn-sm"
                        type="button"
                      >
                        Add {{ (product.price / 100).toFixed(2) }} &euro;
                      </button>
                    </div>
                  </div>

                  <div class="flex-none w-48">
                    <img
                      class="w-full aspect-video rounded"
                      :src="`https://picsum.photos/seed/${product.id}/200/110?blur=2`"
                    />
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
