<script setup lang="ts">
  import { computed, ref, reactive } from 'vue'
  import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue'
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Vue3Datatable from '@bhplugin/vue3-datatable'
  import BaseButtonLink from '@/Components/BaseButtonLink.vue';
  import Modal from '@/Components/Modal.vue';
  import '@bhplugin/vue3-datatable/dist/style.css'

  import { Head, useForm, usePage } from '@inertiajs/vue3'
import { hasPermission } from '@/utils/hasPermission';

  const permissionsData: any = usePage().props.permissionsData



  const form: any = useForm({
    id: 0,
    _method: 'delete'
  })
  const isModalDangerActive = ref(false)
  const isOpen = ref(false);
  const deletePermission = async () => {
    isModalDangerActive.value = false

    await form.delete(route('permissions.destroy', form.id), {
      onSuccess: () => {
        const index = rows.value.findIndex((permission: any) => permission.id === form.id)
        if (index !== -1) {
          rows.value.splice(index, 1)
          rows.value = [...rows.value]
        }
      }
    })

  }

  const closeModal = () => {
    isModalDangerActive.value = false;

    form.reset();
};
  const showModle = (id: number) => {
    isModalDangerActive.value = true
    form.id = id
  }

  const params = reactive({
    current_page: 1,
    pagesize: 10,
    sort_column: 'id',
    sort_direction: 'asc',
    search: '',
  });

  const cols = ref([
    { title: 'SL', field: 'id', isUnique: true, type: 'number', width: '100px', hide: false },
    { title: 'Name', field: 'name', hide: false },
    { title: 'Permission', field: 'permission', hide: false },
    { title: 'Created', field: 'created_at', hide: false },
    { title: 'Updated', field: 'updated_at', hide: false },
    { title: 'Action', field: 'action', hide: false },
  ])

  const rows = ref(permissionsData.map((permission: any, index: number) => {
    return {
      sl: index + 1,
      id: permission.id,
      name: permission.name,
      permission: permission.permission,
      created_at: permission.created_at,
      updated_at: permission.updated_at,
    }
  }))

  const filterdJobList = computed(() => {
    if (!params.search) return permissionsData.slice(0, params.pagesize);
    const query = params.search.toLowerCase();
    return permissionsData?.filter((item: any) => item.name.toLowerCase().includes(query));
  })
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Permissions" />
    <!-- Breadcrumb Start -->
    <BreadcrumbDefault pageTitle="Permissions" />
    <!-- Breadcrumb End -->

    <div class="flex flex-col gap-10">
      <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
        <div class="flex justify-between px-3 pt-4">
                <div class="mb-5 relative">
                  <button type="button" class="bg-slate-800 text-white p-2 inline-block rounded" @click="isOpen = !isOpen">
                      Column Compose <i :class="isOpen ? 'fa fa-chevron-up' : 'fa fa-chevron-down'" aria-hidden="true"></i>
                  </button>
                  <ul v-if="isOpen" class="absolute left-0 mt-0.5 p-2.5 min-w-[150px] bg-white rounded shadow-md space-y-1 z-10">
                      <li v-for="col in cols" :key="col.field">
                          <label class="flex items-center gap-2 w-full cursor-pointer text-gray-600 hover:text-black">
                              <input type="checkbox" class="form-checkbox" :checked="!col.hide" @change="col.hide = !($event.target as HTMLInputElement)?.checked" />
                              <span>{{ col.title }}</span>
                          </label>
                      </li>
                  </ul>
                </div>
                <input
                  type="text"
                  placeholder="Search"
                  class="rounded-lg border-[1.5px] text-black border-stroke bg-transparent py-3 px-5 font-normal outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:text-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                  v-model="params.search" 
                />
                
            </div>
            <Vue3Datatable 
              :rows="rows" 
              :columns="cols" 
              :sortable="true"
              :sortColumn="params.sort_column"
              :sortDirection="params.sort_direction"
              :search="params.search"
              :columnFilter="true"
              @change="filterdJobList"
              :cloneHeaderInFooter="true"
              skin="bh-table-compact"
              class="column-filter p-4"
              rowClass="bg-white dark:bg-slate-800 dark:text-slate-300 dark:border-gray-600"
            >
              <template #action="data">
                <template class="flex">
                  <BaseButtonLink v-if="hasPermission('permission.update')" routeName="permissions.edit" :routeParams="data.value.id" icon="fas fa-edit" label="Edit" color="info" small />
                  <BaseButtonLink v-if="hasPermission('permission.delete')" class="ml-2" icon="fas fa-trash-alt" label="Delete" color="danger" small @click="showModle(data.value.id)"/>
                </template>
              </template>
            </Vue3Datatable>
      </div>
    </div>

    <Modal :show="isModalDangerActive" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-red-900 dark:text-red-100">
                    Are you sure you want to delete permission?
                </h2>
                <div class="mt-6 flex justify-end">
                  <BaseButtonLink class="ml-2" icon="fas fa-trash-alt" label="Delete" color="danger" small @click="deletePermission"/>
                </div>
            </div>
        </Modal>
  </AuthenticatedLayout>
</template>