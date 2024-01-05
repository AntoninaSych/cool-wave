<!-- This example requires Tailwind CSS v2.0+ -->
<template>
  <div class="flex items-center justify-between mb-3">
    <h1 v-if="!loading" class="text-3xl font-semibold">
      {{ page.id ? `Update page: "${page.title}"` : 'Create new Page' }}
    </h1>
  </div>
  <div class="bg-white rounded-lg shadow animate-fade-in-down">
    <Spinner v-if="loading"
             class="absolute left-0 top-0 bg-white right-0 bottom-0 flex items-center justify-center z-50"/>
    <form v-if="!loading" @submit.prevent="onSubmit">
      <div class="grid grid-cols-3">
        <div class="col-span-2 px-4 pt-5 pb-4">
          <CustomInput class="mb-2" v-model="page.name" label="Display in Menu" :errors="errors['name']"/>
          <CustomInput class="mb-2" v-model="page.title" label="Page Title" :errors="errors['title']"/>
          <CustomInput class="mb-2" v-model="page.meta" label="Meta Tags" :errors="errors['meta']"/>
          <span class="text-gray-700">Short Description:</span>
          <CustomInput type="richtext" class="mb-2" v-model="page.short_description" label="Short Description"
                       :errors="errors['short_description']"/>
          <span class="text-gray-700">Long Description:</span>
          <CustomInput type="richtext" class="mb-2" v-model="page.long_description" label="Long Description"
                       :errors="errors['long_description']"/>
          <CustomInput type="checkbox" class="mb-2" v-model="page.type" label="Page"
                       :errors="errors['type']"/>
          <CustomInput type="checkbox" class="mb-2" v-model="page.published" label="Published"
                       :errors="errors['published']"/>
        </div>
      </div>
      <footer class="bg-gray-50 rounded-b-lg px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
        <button type="submit"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save
        </button>
        <button type="button"
                @click="onSubmit($event, true)"
                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm
                          text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
          Save & Close
        </button>
        <router-link :to="{name: 'app.pages'}"
                     class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 text-base font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                     ref="cancelButtonRef">
          Cancel
        </router-link>
      </footer>
    </form>
  </div>
</template>

<script setup>
import {onMounted, ref} from 'vue'
import Treeselect from 'vue3-treeselect'
import 'vue3-treeselect/dist/vue3-treeselect.css'
import CustomInput from "../../components/core/CustomInput.vue";
import store from "../../store/index.js";
import Spinner from "../../components/core/Spinner.vue";
import {useRoute, useRouter} from "vue-router";
import ImagePreview from "../../components/ImagePreview.vue";
import axiosClient from "../../axios.js";

const route = useRoute()
const router = useRouter()
const errors = ref({});
const page = ref({
  id: null,
  title: null,
  name: null,
  meta: null,
  short_description: '',
  long_description: '',
  published: false,
  type: false,
})

const loading = ref(false)
const options = ref([])

const emit = defineEmits(['update:modelValue', 'close' ])

onMounted(() => {
  if (route.params.id) {
    loading.value = true
    store.dispatch('getPage', route.params.id)
        .then((response) => {
          loading.value = false;
          page.value = response.data
        })
  }
})

function onSubmit($event, close = false) {
  loading.value = true

  if (page.value.id) {
    store.dispatch('updatePage', page.value)
        .then(response => {
          loading.value = false;
          if (response.status === 200) {
            page.value = response.data
            store.commit('showToast', 'Page was successfully updated');
            store.dispatch('getPages')
            if (close) {
              router.push({name: 'app.pages'})
            }
            errors.value={}
          }

        })
        .catch(err => {
          loading.value = false;
          errors.value = err.response.data.errors()
        })
  } else {
    store.dispatch('createPage', page.value)
        .then(response => {
          loading.value = false;
          if (response.status === 201) {
            page.value = response.data
            store.commit('showToast', 'Page was successfully created');
            store.dispatch('getPages')
            if (close) {
              router.push({name: 'app.pages'})
            } else {
              console.log(response);
              page.value = response.data
              router.push({name: 'app.pages.edit', params: {id: response.data.id}})
            }
            errors.value={}
          }

        })
        .catch(err => {
          loading.value = false;
          errors.value = err.response.data.errors()
        })
  }
}
</script>