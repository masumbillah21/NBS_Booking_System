<script setup lang="ts">
import { ref, reactive, computed } from 'vue';
import BreadcrumbDefault from '@/Components/Breadcrumbs/BreadcrumbDefault.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Vue3Datatable from '@bhplugin/vue3-datatable';
import '@bhplugin/vue3-datatable/dist/style.css';
import { Head, useForm, usePage } from '@inertiajs/vue3';

// Getting props from the Inertia page
let appointmentData: any = usePage().props.appointments;
const filterItems: any = usePage().props.filterItems;

// State for column visibility
const isOpen = ref(false);

// Table parameters
const params = reactive({
  current_page: 1,
  pagesize: 10,
  sort_column: 'sl',
  sort_direction: 'asc',
  search: '',
});

// Form for filters
const form = useForm({
  timeFrame: filterItems.timeFrame || 'today',
  status: filterItems.status || 'all'
});

// Define table columns
const cols = ref([
  { title: 'SL', field: 'sl', isUnique: true, type: 'number', width: '40px', hide: false },
  { title: 'Service Name', field: 'service_name', width: '200px', hide: false },
  { title: 'Client Name', field: 'client_name', width: '200px', hide: false },
  { title: 'Staff Name', field: 'staff_name', width: '200px', hide: false },
  { title: 'Appointment Date', field: 'appointment_date', hide: false },
  { title: 'Service Price', field: 'price', width: '200px', hide: false },
  { title: 'Status', field: 'status', width: '200px', hide: false },
]);

// Process rows for display
const rows = ref(appointmentData.map((appointment: any, index: number) => {
  return {
    sl: index + 1,
    service_name: appointment.service.service_name,
    client_name: appointment.client.name,
    staff_name: appointment.staff.name,
    appointment_date: appointment.appointment_date,
    price: appointment.service.price,
    status: appointment.status,
  };
}));

// Fetch reports based on filters
const fetchReports = () => {
  form.get(route('reports.appointments.client'), {
    onSuccess: (page: any) => {
      appointmentData = page.props.appointments;
      rows.value = appointmentData.map((appointment: any, index: number) => {
        return {
          sl: index + 1,
          service_name: appointment.service.service_name,
          client_name: appointment.client.name,
          staff_name: appointment.staff.name,
          appointment_date: appointment.appointment_date,
          price: appointment.service.price,
          status: appointment.status,
        };
      });
    }
  });
};

// Filter rows based on search
const filteredRows = computed(() => {
  if (!params.search) return rows.value;
  const query = params.search.toLowerCase();
  return rows.value.filter((row: any) => {
    return row.service_name.toLowerCase().includes(query) ||
           row.client_name.toLowerCase().includes(query) ||
           row.staff_name.toLowerCase().includes(query) ||
           row.status.toLowerCase().includes(query);
  });
});

// Toggle column visibility
const toggleColumnVisibility = (col: any) => {
  col.hide = !col.hide;
}

// Update sorting direction
const updateSorting = (sortColumn: string, sortDirection: string) => {
  params.sort_column = sortColumn;
  params.sort_direction = sortDirection;
}

// Toggle column filter visibility
const toggleColumnFilter = () => {
  isOpen.value = !isOpen.value;
}

// Export report functionality
const exportReport = () => {
  // Logic to export the report, could be a call to a backend route that generates a CSV or PDF
  alert("Report export functionality not implemented yet.");
}
</script>



<template>
    <AuthenticatedLayout>
      <Head title="Client Appointments Report" />
      <BreadcrumbDefault pageTitle="Client Appointments Report" />
  
      <div class="flex flex-col gap-10">
        <div class="rounded-sm border border-stroke bg-white px-5 pt-6 pb-2.5 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
          
          <!-- Filter Section -->
          <div class="flex justify-between px-3 pt-4 items-center">
            <div class="relative">
              <button type="button" class="bg-slate-800 text-white p-2 inline-block rounded" @click="isOpen = !isOpen">
                Column Compose <i :class="isOpen ? 'fa fa-chevron-up' : 'fa fa-chevron-down'" aria-hidden="true"></i>
              </button>
              <ul v-if="isOpen" class="absolute left-0 mt-0.5 p-2.5 min-w-[150px] bg-white rounded shadow-md space-y-1 z-10">
                <li v-for="col in cols" :key="col.field">
                  <label class="flex items-center gap-2 w-full cursor-pointer text-gray-600 hover:text-black">
                    <input type="checkbox" class="form-checkbox" :checked="!col.hide" @change="toggleColumnVisibility(col)" />
                    <span>{{ col.title }}</span>
                  </label>
                </li>
              </ul>
            </div>
  
            <!-- Filter Dropdowns and Export Button -->
            <div class="flex gap-4">
              <div class="flex gap-4">
                <select v-model="form.timeFrame" @change="fetchReports">
                  <option value="today">Today</option>
                  <option value="week">This Week</option>
                  <option value="month">This Month</option>
                  <option value="year">This Year</option>
                </select>
                <select v-model="form.status" @change="fetchReports">
                  <option value="all">All Statuses</option>
                  <option value="pending">Pending</option>
                  <option value="confirmed">Confirmed</option>
                  <option value="completed">Completed</option>
                  <option value="canceled">Canceled</option>
                </select>
              </div>
              <button class="bg-slate-800 text-white p-2 inline-block rounded hover:bg-slate-700" @click="exportReport">Export Report</button>
            </div>
  
            <!-- Search Input -->
            <input type="text" placeholder="Search" class="rounded-lg border-[1.5px] text-black border-stroke bg-transparent py-3 px-5 font-normal outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:text-white dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary" v-model="params.search" />
          </div>
  
          <!-- Datatable -->
          <Vue3Datatable :rows="filteredRows" :columns="cols" :sortable="true" :sortColumn="params.sort_column" :sortDirection="params.sort_direction" :search="params.search" :columnFilter="true" @change="updateSorting" :cloneHeaderInFooter="true" skin="bh-table-compact" class="column-filter p-4" rowClass="bg-white dark:bg-slate-800 dark:text-slate-300 dark:border-gray-600">
          </Vue3Datatable>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  

  