<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Daftar Karyawan dan Divisi</h1>

    <!-- Loading indicator -->
    <div v-if="loading" class="text-center py-4">
      <p>Loading...</p>
    </div>

    <!-- Error message -->
    <div
      v-if="error"
      class="bg-red-100 text-red-700 p-4 mb-4 rounded-lg"
    >
      {{ error }}
    </div>

    <!-- Data Table -->
    <div v-if="!loading && !error" class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              NIK
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Nama Lengkap
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Email
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Telepon
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Divisi
            </th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
              Gaji
            </th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="employee in employees" :key="employee.id">
            <td class="px-6 py-4 whitespace-nowrap">{{ employee.nik }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ employee.nama_lengkap }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ employee.email }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ employee.telepon }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ employee.nama_divisi }}</td>
            <td class="px-6 py-4 whitespace-nowrap">
              {{ formatCurrency(employee.gaji) }}
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Pagination -->
      <div v-if="pagination" class="px-6 py-3 flex justify-between items-center bg-gray-50">
        <div class="flex-1 flex justify-between sm:hidden">
          <button
            @click="changePage(pagination.current_page - 1)"
            :disabled="!pagination.prev_page_url"
            class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Previous
          </button>
          <button
            @click="changePage(pagination.current_page + 1)"
            :disabled="!pagination.next_page_url"
            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
          >
            Next
          </button>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
          <div>
            <p class="text-sm text-gray-700">
              Showing
              <span class="font-medium">{{ pagination.from }}</span>
              to
              <span class="font-medium">{{ pagination.to }}</span>
              of
              <span class="font-medium">{{ pagination.total }}</span>
              results
            </p>
          </div>
          <div>
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
              <button
                v-for="page in pagination.last_page"
                :key="page"
                @click="changePage(page)"
                :class="[
                  page === pagination.current_page
                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                  'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                ]"
              >
                {{ page }}
              </button>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'EmployeeList',
  data() {
    return {
      employees: [],
      pagination: null,
      loading: false,
      error: null
    };
  },
  mounted() {
    this.fetchEmployees();
  },
  methods: {
    async fetchEmployees(page = 1) {
      this.loading = true;
      this.error = null;
      try {
        const response = await axios.get(`/api/employees-with-division?page=${page}`);
        if (response.data.data) {
          this.employees = response.data.data.data;
          this.pagination = {
            current_page: response.data.data.current_page,
            last_page: response.data.data.last_page,
            from: response.data.data.from,
            to: response.data.data.to,
            total: response.data.data.total,
            prev_page_url: response.data.data.prev_page_url,
            next_page_url: response.data.data.next_page_url
          };
        }
      } catch (error) {
        this.error = 'Gagal mengambil data karyawan';
        console.error('Error fetching employees:', error);
      } finally {
        this.loading = false;
      }
    },

    changePage(page) {
      this.fetchEmployees(page);
    },

    formatCurrency(value) {
      return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
      }).format(value);
    }
  }
};
</script> 