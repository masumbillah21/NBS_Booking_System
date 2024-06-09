<script setup lang="ts">
import { computed, ref, reactive } from 'vue'
import { Head, useForm, usePage } from '@inertiajs/vue3'
import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Vue3Datatable from '@bhplugin/vue3-datatable'
import Modal from '@/Components/Modal.vue';
import '@bhplugin/vue3-datatable/dist/style.css'
import BaseButtonLink from '@/Components/BaseButtonLink.vue';
import { hasPermission } from '@/utils/hasPermission';

const appointmentData: any = usePage().props.customerAppointments

const form: any = useForm({
  id: 0,
  _method: 'put'
})


const isModalCancelActive = ref(false)
// const isModalReappointActive = ref(false);
const isOpen = ref(false);

const closeModal = () => {
  
  isModalCancelActive.value = false;
  // isModalReappointActive.value = false;
  form.reset();
};



const cancelAppointment = async () => {
  isModalCancelActive.value = false;
  form.put(route('customer.cancel', form.id), {
    onSuccess: () => {
      const index = rows.value.findIndex((customer: any) => customer.id === form.id);
      if (index !== -1) {
        rows.value[index].status = 'canceled'; // Update status to cancelled
        rows.value = [...rows.value];
      }
    }
  });
};

// const reappointment = async () => {
//   isModalReappointActive.value = false;
//   form.put(route('customer.reappointment', form.id), {
//     onSuccess: () => {
//       const index = rows.value.findIndex((customer: any) => customer.id === form.id);
//       if (index !== -1) {
//         rows.value[index].status = 'pending'; // Update status to pending or your preferred initial status
//         rows.value = [...rows.value];
//       }
//     }
//   });
// };

const showModal = (id: string | number, type: 'cancel') => {
  form.id = id;
   if (type === 'cancel') {
    isModalCancelActive.value = true;
  } 
};

const params = reactive({
  current_page: 1,
  pagesize: 10,
  sort_column: 'sl',
  sort_direction: 'asc',
  search: '',
});

const cols = ref([
  { title: 'SL', field: 'sl', isUnique: true, type: 'number', width: '40px', hide: false },
  { title: 'Client Name', field: 'client_name', width: '200px', hide: false },
  { title: 'Staff Name', field: 'staff_name', width: '200px', hide: false },
  { title: 'Service Name', field: 'service_name', width: '200px', hide: false },
  { title: 'Appointment Date', field: 'appointment_date', hide: false },
  { title: 'Appointment Time', field: 'appointment_time', width: '200px', hide: false },
  { title: 'Status', field: 'status', width: '200px', hide: false },
  { title: 'Action', field: 'action', width: '200px', hide: false },
])

const rows = ref(appointmentData.map((customer: any, index: number) => {
  return {
    sl: index + 1,
    id: customer.id,
    client_name: customer.client.name,
    staff_name: customer.staff.name,
    service_name: customer.service.service_name,
    appointment_date: customer.appointment_date,
    appointment_time: customer.appointment_time,
    status: customer.status,
    created_at: customer.created_at,
    updated_at: customer.updated_at,
  }
}))

const filteredService = computed(() => {
  if (!params.search) return rows.value.slice(0, params.pagesize);
  const query = params.search.toLowerCase();
  return rows.value.filter((item: any) => item.service_name.toLowerCase().includes(query));
})
</script>

<template>
  <AuthenticatedLayout>
    <Head title="Customer" />
    <!-- Breadcrumb Start -->
    <BreadcrumbDefault pageTitle="Customer" />
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
          <input type="text" placeholder="Search"
            class="rounded-lg border-[1.5px] text-black border-stroke bg-transparent py-3 px-5 font-normal outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:text-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
            v-model="params.search" />
        </div>
        <Vue3Datatable :rows="filteredService" :columns="cols" :sortable="true" :sortColumn="params.sort_column"
          :sortDirection="params.sort_direction" :search="params.search" :columnFilter="true"
          :cloneHeaderInFooter="true" skin="bh-table-compact" class="column-filter p-4"
          rowClass="bg-white dark:bg-slate-800 dark:text-slate-300 dark:border-gray-600">
          <template #action="data">
            <template class="flex">
              <BaseButtonLink  v-if="data.value.status !== 'canceled' && data.value.status !== 'completed' && hasPermission('customer.edit')" routeName="customer.edit" :routeParams="data.value.id" icon="fas fa-edit" label="Edit"
                color="info" small class="ml-2" />
                <BaseButtonLink  v-if="data.value.status === 'completed' && hasPermission('customer.feedback')" routeName="feedback.create" :routeParams="data.value.id" icon="fas fa-edit" label="Feedback Create"
                color="info" small class="ml-2" />
                <BaseButtonLink  v-if="(data.value.status === 'completed' || data.value.status === 'canceled') && hasPermission('customer.reappointment')" routeName="customer.reappointment.create" :routeParams="data.value.id" icon="fas fa-redo" label="Reappointment Create"
                color="success" small class="ml-2" />
              <BaseButtonLink v-if="data.value.status !== 'completed' && data.value.status !=='canceled' && hasPermission('customer.cancel')" class="ml-2" icon="fas fa-times" label="Cancel" color="warning" small
                @click="showModal(data.value.id, 'cancel')" />
             
            </template>
          </template>
        </Vue3Datatable>
      </div>
    </div>

    <!-- Cancel Modal -->
    <Modal :show="isModalCancelActive" @close="closeModal">
      <div class="p-6">
        <h2 class="text-lg font-medium text-red-900 dark:text-red-100">Are you sure you want to cancel this appointment?</h2>
        <div class="mt-6 flex justify-end">
          <button @click="cancelAppointment" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
          <button @click="closeModal" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">Close</button>
        </div>
      </div>
    </Modal>

    
  </AuthenticatedLayout>
</template>
