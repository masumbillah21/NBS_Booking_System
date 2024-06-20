<script setup lang="ts">
  import { computed, ref, reactive } from 'vue'
  import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue'
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Vue3Datatable from '@bhplugin/vue3-datatable'
  import '@bhplugin/vue3-datatable/dist/style.css'
  import { Head, useForm, usePage, router } from '@inertiajs/vue3'
  import InputGroup from '@/Components/Forms/InputGroup.vue';


  let appointmentData: any = usePage().props.appointments
  const filterItems: any = usePage().props.filterItems

  const isOpen = ref(false);

  const params = reactive({
    current_page: 1,
    pagesize: 10,
    sort_column: 'sl',
    sort_direction: 'asc',
    search: '',
  });

  const form = useForm({
    timeFrame: filterItems.timeFrame || 'today',
    status: filterItems.status || 'all',
    startDate: '',
    endDate: ''
  })


  const cols = ref([
    { title: 'SL', field: 'sl', isUnique: true, type: 'number', width: '40px', hide: false },
    { title: 'Service Name', field: 'service_name', width: '200px', hide: false },
    { title: 'Client Name', field: 'client_name', width: '200px', hide: false },
    { title: 'Staff Name', field: 'staff_name', width: '200px', hide: false },
    { title: 'Appoinment Date', field: 'appointment_date', hide: false },
    { title: 'Service Price', field: 'price', width: '200px', hide: false },
    { title: 'Status', field: 'status', width: '200px', hide: false },
  ])

  const rows = ref(appointmentData.map((appointment: any, index: number) => {
    return {
      sl: index + 1,
      service_name: appointment.service.service_name,
      client_name: appointment.client.name,
      staff_name: appointment.staff.name,
      appointment_date: appointment.appointment_date,
      price: appointment.service.price,
      status: appointment.status,
    }
  }))

    appointmentData = usePage().props.appointments
    rows.value = appointmentData.map((appointment: any, index: number) => {
      return {
        sl: index + 1,
        service_name: appointment.service.service_name,
        client_name: appointment.client.name,
        staff_name: appointment.staff.name,
        appointment_date: appointment.appointment_date,
        price: appointment.service.price,
        status: appointment.status,
      }
    })
  
    const fetchReports = () => {
    const queryParams: any = {
      timeFrame: form.timeFrame,
      status: form.status
    };

    if (form.timeFrame === 'custom') {
      queryParams.startDate = form.startDate
      queryParams.endDate = form.endDate
    };

      form.get(route('reports.appointments'), {
        onSuccess: (page: any) => {
          appointmentData = page.props.appointments
          rows.value = appointmentData.map((appointment: any, index: number) => {
            return {
              sl: index + 1,
              service_name: appointment.service.service_name,
              client_name: appointment.client.name,
              staff_name: appointment.staff.name,
              appointment_date: appointment.appointment_date,
              appointment_time: appointment.appointment_time,
              status: appointment.status,
            }
          })
        }
      })
    }

  const filteredService = computed(() => {
    if (!params.search) return appointmentData.slice(0, params.pagesize);
    const query = params.search.toLowerCase();
    return appointmentData?.filter((item: any) => item.service.service_name.toLowerCase().includes(query) || item.status == filterItems.status);
  })

  const openExportPdf = () => {
  form.get(route('reports.appointments.export'));
};
</script>

<template>
  <AuthenticatedLayout>
  <Head title="Reports" />
  <!-- Breadcrumb Start -->
  <BreadcrumbDefault pageTitle="Appointments Reports" />
  <!-- Breadcrumb End -->
  <div class="flex flex-col gap-10">
    <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
      <div class="flex justify-between px-3 pt-4 items-center">
        <div class="relative">
          <button type="button" class="bg-slate-800 text-white p-2 inline-block rounded dark:bg-slate-200 dark:text-slate-800" @click="isOpen = !isOpen">
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
        <!-- Filter Dropdown Start -->
        <div class="flex justify-between items-center gap-4">
        <div class="flex gap-4">
          <select class="border rounded" v-model="form.timeFrame" @change="fetchReports">
            <option value="today">Today</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
            <option value="year">This Year</option>
            <option value="custom">Custom</option>
          </select>
          <select class="border rounded" v-model="form.status" @change="fetchReports">
            <option value="all">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="completed">Completed</option>
            <option value="canceled">Canceled</option>
          </select>
        </div>
        <button class="bg-slate-800 dark:bg-slate-200 dark:text-slate-800 text-white p-2 inline-block rounded hover:bg-slate-700" @click="openExportPdf">Export Report</button>
      </div>
        <input type="text" placeholder="Search"
          class="rounded-lg border-[1.5px] text-black border-stroke bg-transparent py-3 px-5 font-normal outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:text-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
          v-model="params.search" />
      </div>
      <!-- Custom date range -->
      <div v-if="form.timeFrame === 'custom'" class="flex flex-col gap-4 justify-items-center items-center">
        <h2 class="text-xl font-bold text-black dark:text-white">Custom date range</h2>
        <div class="flex flex-row gap-4 border rounded border-stroke px-4" >
        <InputGroup label="Start Date" class="mb-2" type="date" v-model="form.startDate">
        </InputGroup>
        <InputGroup label="End Date" class="mb-2" type="date" v-model="form.endDate">
        </InputGroup>
        <div class="mt-13">
          <button class="bg-slate-800 text-white p-2 rounded hover:bg-slate-700 dark:bg-slate-200 dark:text-slate-800" @click="fetchReports">Apply</button>
        </div>
        </div>
      </div>
      <Vue3Datatable :rows="rows" :columns="cols" :sortable="true" :sortColumn="params.sort_column"
        :sortDirection="params.sort_direction" :search="params.search" :columnFilter="true" @change="filteredService"
        :cloneHeaderInFooter="true" skin="bh-table-compact" class="column-filter p-4"
        rowClass="bg-white dark:bg-slate-800 dark:text-slate-300 dark:border-gray-600">
      </Vue3Datatable>
    </div>
  </div>
</AuthenticatedLayout>
</template>
