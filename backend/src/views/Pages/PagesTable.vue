<template>
  <div class="bg-white p-4 rounded-lg shadow animate-fade-in-down">
    <div class="flex justify-between border-b-2 pb-3">
      <div class="flex items-center">
        <span class="whitespace-nowrap mr-3"> Per Page </span>
        <select @change="getPages(null)" v-model="perPage"
                class="appearance-none relative block w-24 px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="20">20</option>
          <option value="50">50</option>
          <option value="100">100</option>
        </select>
      </div>
      <div>
        <input v-model="search" @change="getPages(null)"
               class="appearance-none relative block w-48 px-3 py-2 border border-gray-300
                    placeholder-gray-500 text-gray-900 rounded-md
                     focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm"
               placeholder="Type to Search Pages">
      </div>
    </div>


    <table class="table-auto w-full">
      <thead>
      <tr>
        <TableHeaderCell @click="sortPage('id')" class="border-b-2 p-2 text-left" field="id"
                         :sort-field="sortField"
                         :sort-direction="sortDirection">ID
        </TableHeaderCell>

        <TableHeaderCell @click="sortPage('title')" class="border-b-2 p-2 text-left" field="title"
                         :sort-field="sortField"
                         :sort-direction="sortDirection">Title
        </TableHeaderCell>

        <TableHeaderCell @click="sortPage('type')" class="border-b-2 p-2 text-left" field="type"
                         :sort-field="sortField"
                         :sort-direction="sortDirection">Type
        </TableHeaderCell>
        <TableHeaderCell @click="sortPage('published')" class="border-b-2 p-2 text-left" field="type"
                         :sort-field="sortField"
                         :sort-direction="sortDirection">Published
        </TableHeaderCell>
        <TableHeaderCell @click="sortPage('updated_at')" class="border-b-2 p-2 text-left" field="updated_at"
                         :sort-field="sortField" :sort-direction="sortDirection">Last Updated At
        </TableHeaderCell>
        <TableHeaderCell field="actions">
          Actions
        </TableHeaderCell>
      </tr>
      </thead>
      <tbody v-if="pages.loading">
      <tr>
        <td colspan="6">
          <Spinner class="my-4"/>
        </td>
      </tr>
      </tbody>
      <tbody v-else>
<!--            <tr v-for="(page, index) of pages.data" class="shadow animate-fade-in-down" :style="{'animation-delay': `${index * 0.1}s`}" >-->
      <tr v-for="(page, index) of pages.data">
        <td class="border-b p-2"> {{ page.id }}</td>
        <td class="border-b p-2 max-w-[200px] whitespace-nowrap overflow-hidden text-ellipsis">{{
            page.title
          }}
        </td>
        <td class="border-b p-2">    {{ page.type ? "Page" : "Article"}}</td>
        <td class="border-b p-2">    {{ page.type ? "Yes" : "No"}}</td>
        <td class="border-b p-2"> {{ page.updated_at }}</td>
        <td class="border-b p-2">
          <Menu as="div" class="relative inline-block text-left">
            <div>
              <MenuButton
                  class="inline-flex items-center justify-center w-full justify-center rounded-full w-10 h-10 bg-black bg-opacity-0 text-sm font-medium text-white hover:bg-opacity-5 focus:bg-opacity-5 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-opacity-75"
              >
                <DotsVerticalIcon
                    class="h-5 w-5 text-indigo-500"
                    aria-hidden="true"/>
              </MenuButton>
            </div>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
              <MenuItems
                  class="absolute z-10 right-0 mt-2 w-32 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
              >
                <div class="px-1 py-1">
                  <MenuItem v-slot="{ active }">
                    <router-link :to="{name:'app.pages.edit', params:{id:page.id}}"
                        :class="[
                        active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"

                    >
                      <PencilIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-indigo-400"
                          aria-hidden="true"
                      />
                      Edit
                    </router-link>
                  </MenuItem>
                  <MenuItem v-slot="{ active }">
                    <button
                        :class="[
                        active ? 'bg-indigo-600 text-white' : 'text-gray-900',
                        'group flex w-full items-center rounded-md px-2 py-2 text-sm',
                      ]"
                        @click="deletePage(page)"
                    >
                      <TrashIcon
                          :active="active"
                          class="mr-2 h-5 w-5 text-indigo-400"
                          aria-hidden="true"
                      />
                      Delete
                    </button>
                  </MenuItem>
                </div>
              </MenuItems>
            </transition>
          </Menu>
        </td>
      </tr>
      </tbody>
    </table>
    <div v-if="!pages.loading" class="flex justify-between items-center mt-5">
        <span>
          Showing from {{ pages.from }} to {{ pages.to }}
        </span>
      <nav
          v-if="pages.total > pages.limit"
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
      ><a v-for="(link, i) of pages.links"
          :key="i"
          :disabled="!link.url"
          href="#"
          @click.prevent="getForPage($event, link)"
          aria-current="page"
          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
          :class="[
                link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                i === 0 ? 'rounded-l-md' : '' ,
                i === pages.links.length -1 ? 'rounded-r-md' : '',
                !link.url ? 'bg-gray-100 text-gray-700' : ''
            ]"
          v-html="link.label"
      >
      </a>
      </nav>
    </div>

  </div>
</template>

<script setup>
import Spinner from "../../components/core/Spinner.vue";
import {computed, onMounted, ref} from "vue";
import store from "../../store/index.js";
import {PAGES_PER_PAGE} from "../../constants.js";
import TableHeaderCell from "../../components/Table/TableHeaderCell.vue";
import {Menu, MenuButton, MenuItems, MenuItem} from '@headlessui/vue'
import {DotsVerticalIcon, PencilIcon, TrashIcon} from '@heroicons/vue/outline'


const emit = defineEmits(['clickEdit'])
const perPage = ref(PAGES_PER_PAGE)
const search = ref('')
const pages = computed(() => store.state.pages)
const sortField = ref('updated_at')
const sortDirection = ref('desc')


onMounted(() => {
  getPages(null);
})

function getPages(url = null) {
  store.dispatch('getPages', {
    url,
    search: search.value,
    per_page: perPage.value,
    sort_field: sortField.value,
    sort_direction: sortDirection.value,
  })
}

function getForPage(event, link) {
  if (!link.url || link.active) {
    return
  }
  getPages(link.url)
}

function sortPage(field) {
  if (field === sortField.value) {
    if (sortDirection.value === 'asc') {
      sortDirection.value = 'desc'
    } else {
      sortDirection.value = 'asc'
    }
  } else {
    sortField.value = field;
    sortDirection.value = 'asc'
  }
  getPages()
}

function deletePage(page) {
  //TODO replace it with swal or sweet alert
  if (!confirm(`Are you sure you want to delete the page?`)) {
    return
  }

  store.dispatch('deletePage', page.id)
      .then(res => {
        store.commit('showToast', `Page was successfully removed`)
        store.dispatch('getPages')
      })
}


</script>

<style scoped>

</style>