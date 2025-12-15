<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="flex items-center justify-between mb-3">
    <h1 v-if="!loading" class="text-3xl font-semibold">
      {{ product.id ? `Update product: "${product.title}"` : 'Create new Product' }}
    </h1>
  </div>

  <div class="bg-white rounded-lg shadow animate-fade-in-down">
    <Spinner
        v-if="loading"
        class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center z-50"
    />

    <form v-if="!loading" @submit.prevent="onSubmit">
      <div class="grid grid-cols-3">
        <!-- LEFT -->
        <div class="col-span-2 px-4 pt-5 pb-4">
          <CustomInput
              class="mb-2"
              v-model="product.title"
              label="Product Title"
              :errors="errors['title']"
          />

          <CustomInput
              type="richtext"
              class="mb-2"
              v-model="product.description"
              label="Description"
              editorConfig=""
              :errors="errors['description']"
          />

          <CustomInput
              type="number"
              class="mb-2"
              v-model="product.price"
              label="Price"
              prepend="$"
              :errors="errors['price']"
          />

          <CustomInput
              type="checkbox"
              class="mb-2"
              v-model="product.published"
              label="Published"
              :errors="errors['published']"
          />

          <treeselect
              v-model="product.categories"
              :multiple="true"
              :options="options"
              :errors="errors['categories']"
          />
        </div>

        <!-- RIGHT -->
        <div class="col-span-1 px-4 pt-5 pb-4">
          <ImagePreview
              :images="product.images"
              :deleted-images="product.deleted_images"
              :image-positions="product.image_positions"
              @update:images="val => product.images = val"
              @update:deleted-images="val => product.deleted_images = val"
              @update:image-positions="val => product.image_positions = val"
          />
        </div>
      </div>

      <footer class="bg-gray-50 rounded-b-lg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button
            type="submit"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium
                 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                 text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500"
        >
          Save
        </button>

        <button
            type="button"
            @click="onSubmit($event, true)"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium
                 focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                 text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500"
        >
          Save & Close
        </button>

        <router-link
            :to="{ name: 'app.products' }"
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium
                 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
        >
          Cancel
        </router-link>
      </footer>
    </form>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'

import CustomInput from "../../components/core/CustomInput.vue"
import Spinner from "../../components/core/Spinner.vue"
import ImagePreview from "../../components/ImagePreview.vue"

import store from "../../store/index.js"
import axiosClient from "../../axios.js"

const route = useRoute()
const router = useRouter()

const loading = ref(false)
const errors = ref({})
const options = ref([])

const product = ref({
  id: null,
  title: null,
  images: [],
  deleted_images: [],
  image_positions: {},
  description: '',
  price: null,
  published: false,
  categories: []
})

onMounted(() => {
  if (route.params.id) {
    loading.value = true
    store.dispatch('getProduct', route.params.id)
        .then(response => {
          loading.value = false

          product.value = {
            ...response.data,
            images: response.data.images || [],
            deleted_images: [],
            image_positions: {}
          }
        })
  }

  axiosClient.get('/categories/tree')
      .then(result => {
        options.value = result.data
      })
})

function onSubmit(event, close = false) {
  loading.value = true

  const action = product.value.id ? 'updateProduct' : 'createProduct'

  store.dispatch(action, product.value)
      .then(() => {
        loading.value = false
        store.dispatch('getProducts')

        if (close) {
          router.push({ name: 'app.products' })
        }

        errors.value = {}
      })
      .catch(err => {
        loading.value = false
        errors.value = err.response?.data?.errors || {}
      })
}
</script>
