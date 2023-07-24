<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head } from '@inertiajs/vue3'
import AddStaffMemberForm from '@/Pages/Vendor/Staff/Partials/AddStaffMemberForm.vue'
</script>

<template>
  <Head title="Staff Management" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Staff Management</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div v-if="can('user.create')" class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <AddStaffMemberForm />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
