<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SelectInput from '@/Components/SelectInput.vue'
import TextareaInput from '@/Components/TextareaInput.vue'
import TextInput from '@/Components/TextInput.vue'

const props = defineProps({
  cities: {
    type: Array
  },
  restaurant: {
    type: Object
  }
})

const form = useForm({
  restaurant_name: props.restaurant.name,
  email: props.restaurant.owner.email,
  owner_name: props.restaurant.owner.name,
  city_id: props.restaurant.city_id,
  address: props.restaurant.address
})

const submit = () => {
  form.patch(route('admin.restaurants.update', props.restaurant))
}
</script>

<template>
  <Head :title="'Edit ' + restaurant.name" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ 'Edit ' + restaurant.name }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900 overflow-x-scroll">
            <div class="p-6 text-gray-900 overflow-x-scroll">
              <form @submit.prevent="submit" class="flex flex-col gap-4">
                <div class="form-group">
                  <InputLabel for="restaurant_name" value="Restaurant Name" />
                  <TextInput
                    id="restaurant_name"
                    type="text"
                    v-model="form.restaurant_name"
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.restaurant_name" />
                </div>

                <div class="form-group">
                  <InputLabel for="email" value="Email" />
                  <TextInput id="email" type="email" v-model="form.email" :disabled="true" />
                  <InputError :message="form.errors.email" />
                </div>

                <div class="form-group">
                  <InputLabel for="owner_name" value="Owner Name" />
                  <TextInput
                    id="owner_name"
                    type="text"
                    v-model="form.owner_name"
                    :disabled="true"
                  />
                  <InputError :message="form.errors.owner_name" />
                </div>

                <div class="form-group">
                  <InputLabel for="city_id" value="City" />
                  <SelectInput
                    id="city_id"
                    v-model="form.city_id"
                    :options="cities"
                    option-value="id"
                    option-label="name"
                    :default-option="{
                      id: '',
                      name: 'City Name'
                    }"
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.city_id" />
                </div>

                <div class="form-group">
                  <InputLabel for="address" value="Address" />
                  <TextareaInput
                    id="address"
                    v-model="form.address"
                    class="resize-none"
                    rows="3"
                    :disabled="form.processing"
                  />
                  <InputError :message="form.errors.address" />
                </div>

                <div>
                  <PrimaryButton :processing="form.processing">Update Restaurant</PrimaryButton>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
