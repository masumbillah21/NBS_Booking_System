<script setup>
import { ref, onMounted } from 'vue';

const appointments = ref([]);

onMounted(() => {
  const data = sessionStorage.getItem('appointments');
  if (data) {
    appointments.value = JSON.parse(data);
  }
});

const printPdf = () => {
  window.print();
};
</script>

<template>
  <div id="print-area" class="container mx-auto p-4 mt-10 text-center">

    <div>
      <h2 class="text-xl font-bold mb-4">Company Name: {{ appointments[0].service.provider.company_name }}</h2>
    </div>
    <h1 class="text-2xl font-bold mb-4">Appointment Report</h1>
    <table class="w-full border border-gray-300 dark:border-gray-700 text-left">
      <thead class="bg-gray-200 dark:bg-gray-700">
        <tr class="text-left">
          <th>SL</th>
          <th>Service Name</th>
          <th>Client Name</th>
          <th>Staff Name</th>
          <th>Appointment Date</th>
          <th>Service Price</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(appointment, index) in appointments" :key="index" class="border border-gray-300 dark:border-gray-700">
          <td>{{ index + 1 }}</td>
          <td>{{ appointment.service.service_name }}</td>
          <td>{{ appointment.client.name }}</td>
          <td>{{ appointment.staff.name }}</td>
          <td>{{ appointment.appointment_date }}</td>
          <td>{{ appointment.service.price }}</td>
          <td>{{ appointment.status }}</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="text-center no-print">
    <button @click="printPdf" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-4">Print PDF</button>
  </div>
</template>

<style scoped>
table {
  width: 100%;
  border-collapse: collapse;
}

th, td {
  border: 1px solid black;
  padding: 8px;
  text-align: left;
}

th {
  background-color: #f2f2f2;
}

@media print {

  .no-print {
    display: none;
  }

  table {
    width: 100%;
    border-collapse: collapse;
  }

  th, td {
    border: 1px solid black;
    padding: 8px;
    text-align: left;
  }

  th {
    background-color: #f2f2f2;
  }
}
</style>
