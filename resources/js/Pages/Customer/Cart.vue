<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const form = useForm({
  product: {}
})

const removeProduct = (uuid) => {
  form.post(route('customer.cart.remove', uuid), { preserveScroll: true })
}
</script>

<template>
  <Head title="Cart" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cart</h2>
    </template>

    <div class="py-12">
      <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 overflow-x-scroll flex flex-col gap-6">
            <div
              v-for="product in $page.props.cart.items"
              :key="product.uuid"
              class="border-b pb-6"
            >
              <div class="flex gap-4">
                <div class="flex-none w-14">
                  <img
                    class="w-full aspect-square rounded"
                    :src="`https://picsum.photos/seed/${product.id}/60/60?blur=2`"
                  />
                </div>
                <div class="flex flex-col">
                  <div>{{ product.name }}</div>
                  <div>{{ (product.price / 100).toFixed(2) }} &euro;</div>
                </div>
                <div class="flex items-center ml-auto">
                  <button
                    type="button"
                    class="btn btn-secondary w-8 h-8 p-4"
                    @click="removeProduct(product.uuid)"
                  >
                    —
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-4">
          <div class="p-6 text-gray-900 font-bold">
            <div class="flex items-center justify-between text-2xl border-b pb-4">
              <div>Total</div>
              <div>{{ ($page.props.cart.total / 100).toFixed(2) }} &euro;</div>
            </div>
            <div class="mt-4" v-if="$page.props.cart.items.length">
              <PrimaryButton type="button">Place Order</PrimaryButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
