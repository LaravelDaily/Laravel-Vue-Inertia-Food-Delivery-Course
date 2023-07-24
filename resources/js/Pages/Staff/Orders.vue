<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'

defineProps({
  current_orders: {
    type: Array
  },
  past_orders: {
    type: Array
  },
  order_status: {
    type: Object
  }
})

const form = useForm({
  status: null
})

const updateStatus = (order, status) => {
  form.status = status
  form.put(route('staff.orders.update', order), {
    preserveScroll: true
  })
}
</script>

<template>
  <Head title="Restaurant Orders" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Restaurant Orders</h2>
    </template>

    <div class="py-12">
      <!-- Current Orders -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 overflow-x-scroll">
            <header>
              <h2 class="text-lg font-medium text-gray-900">Current Orders</h2>
            </header>

            <table class="table mt-6">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Items</th>
                  <th>Customer</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="align-top">
                <tr v-for="order in current_orders" :key="order.id">
                  <td>{{ order.id }}</td>
                  <td>
                    <div v-for="product in order.products" :key="product.id" class="border-b">
                      {{ product.name }}
                    </div>
                  </td>
                  <td>{{ order.customer.name }}</td>
                  <td>
                    <div class="whitespace-nowrap">{{ (order.total / 100).toFixed(2) }} &euro;</div>
                  </td>
                  <td>
                    <div
                      class="badge"
                      :class="{
                        'badge-yellow': order.status === order_status.PENDING,
                        'badge-secondary': order.status === order_status.PREPARING
                      }"
                    >
                      {{ order.status }}
                    </div>
                  </td>
                  <td>
                    <div class="flex flex-row gap-2 justify-end">
                      <SecondaryButton
                        v-if="order.status === order_status.PENDING"
                        @click="updateStatus(order, order_status.PREPARING)"
                        class="btn-sm"
                        type="button"
                      >
                        Prepare
                      </SecondaryButton>
                      <PrimaryButton
                        v-if="order.status === order_status.PREPARING"
                        @click="updateStatus(order, order_status.READY)"
                        class="btn-sm"
                        type="button"
                      >
                        Ready
                      </PrimaryButton>
                      <DangerButton
                        @click="updateStatus(order, order_status.CANCELLED)"
                        class="btn-sm"
                        type="button"
                      >
                        Cancel
                      </DangerButton>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Past Orders -->
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 overflow-x-scroll">
            <header>
              <h2 class="text-lg font-medium text-gray-900">Past Orders</h2>
            </header>

            <table class="table mt-6">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Items</th>
                  <th>Customer</th>
                  <th>Total</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody class="align-top">
                <tr v-for="order in past_orders" :key="order.id">
                  <td>{{ order.id }}</td>
                  <td>
                    <div v-for="product in order.products" :key="product.id" class="border-b">
                      {{ product.name }}
                    </div>
                  </td>
                  <td>{{ order.customer.name }}</td>
                  <td>
                    <div class="whitespace-nowrap">{{ (order.total / 100).toFixed(2) }} &euro;</div>
                  </td>
                  <td>
                    <div
                      class="badge"
                      :class="{
                        'badge-primary': order.status === order_status.READY,
                        'badge-danger': order.status === order_status.CANCELLED
                      }"
                    >
                      {{ order.status }}
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
