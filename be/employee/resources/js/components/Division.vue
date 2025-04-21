<template>
  <div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Manajemen Divisi</h1>

    <!-- Form untuk menambah atau mengedit divisi -->
    <div class="bg-gray-100 p-4 mb-6 rounded-lg">
      <h2 class="text-xl font-semibold mb-3">
        {{ isEditing ? 'Edit Divisi' : 'Tambah Divisi Baru' }}
      </h2>
      <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="nama_divisi"
          >Nama Divisi:</label
        >
        <input
          v-model="form.nama_divisi"
          type="text"
          id="nama_divisi"
          class="w-full p-2 border rounded"
          placeholder="Masukkan nama divisi"
        />
      </div>
      <div class="mb-4">
        <label class="block text-gray-700 mb-2" for="deskripsi"
          >Deskripsi:</label
        >
        <textarea
          v-model="form.deskripsi"
          id="deskripsi"
          class="w-full p-2 border rounded"
          rows="3"
          placeholder="Masukkan deskripsi divisi"
        ></textarea>
      </div>
      <div class="flex space-x-2">
        <button
          @click="saveDivision"
          class="bg-blue-500 text-black px-4 py-2 rounded hover:bg-blue-600"
        >
          {{ isEditing ? 'Update' : 'Simpan' }}
        </button>
        <button
          v-if="isEditing"
          @click="cancelEdit"
          class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
        >
          Batal
        </button>
      </div>
    </div>

    <!-- Alert messages -->
    <div
      v-if="message"
      :class="[
        messageType === 'success'
          ? 'bg-green-100 text-green-700'
          : 'bg-red-100 text-red-700',
        'p-4 mb-4 rounded-lg',
      ]"
    >
      {{ message }}
    </div>

    <!-- Loading indicator -->
    <div v-if="loading" class="text-center py-4">
      <p>Loading...</p>
    </div>

    <!-- Daftar Divisi -->
    <div v-else>
      <h2 class="text-xl font-semibold mb-3">Daftar Divisi</h2>
      <div
        v-if="divisions.length === 0"
        class="bg-gray-50 p-4 text-center rounded"
      >
        Tidak ada data divisi
      </div>
      <div v-else class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                ID
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Nama Divisi
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Deskripsi
              </th>
              <th
                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
              >
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="division in divisionData" :key="division.id">
              <td class="px-6 py-4 whitespace-nowrap">{{ division.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                {{ division.nama_divisi }}
              </td>
              <td class="px-6 py-4">{{ division.deskripsi }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="editDivision(division)"
                  class="text-blue-600 hover:text-blue-900 mr-2"
                >
                  Edit
                </button>
                <button
                  @click="deleteDivision(division.id)"
                  class="text-red-600 hover:text-red-900"
                >
                  Hapus
                </button>
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
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'Division',
  data() {
    return {
      divisionData: [],
      pagination: null,
      form: {
        nama_divisi: '',
        deskripsi: '',
      },
      isEditing: false,
      currentId: null,
      loading: false,
      message: '',
      messageType: 'success',
    };
  },
  mounted() {
    this.fetchDivisions();
  },
  methods: {
    // Ambil semua data divisi
    async fetchDivisions(page = 1) {
      this.loading = true;
      try {
        const response = await axios.get(`/api/division?page=${page}`);
        if (response.data.data) {
          this.divisionData = response.data.data.data;
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
        this.loading = false;
      } catch (error) {
        this.showMessage('Gagal mengambil data divisi', 'error');
        console.error('Error fetching divisions:', error);
        this.loading = false;
      }
    },

    // Ganti halaman
    changePage(page) {
      this.fetchDivisions(page);
    },

    // Simpan divisi (tambah atau update)
    async saveDivision() {
      if (!this.form.nama_divisi || !this.form.deskripsi) {
        this.showMessage('Nama divisi dan deskripsi harus diisi', 'error');
        return;
      }

      this.loading = true;
      try {
        if (this.isEditing) {
          // Update existing division
          const response = await axios.put(
            `/api/division/${this.currentId}`,
            this.form
          );
          this.showMessage(response.data.message, 'success');
        } else {
          // Create new division
          const response = await axios.post('/api/division', this.form);
          this.showMessage(response.data.message, 'success');
        }

        // Reset form and fetch updated data
        this.resetForm();
        this.fetchDivisions();
      } catch (error) {
        const message = error.response?.data?.message || 'Terjadi kesalahan saat menyimpan data';
        this.showMessage(message, 'error');
        console.error('Error saving division:', error);
        this.loading = false;
      }
    },

    // Edit divisi
    editDivision(division) {
      this.isEditing = true;
      this.currentId = division.id;
      this.form.nama_divisi = division.nama_divisi;
      this.form.deskripsi = division.deskripsi;
      // Scroll to form
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },

    // Batalkan edit
    cancelEdit() {
      this.resetForm();
    },

    // Hapus divisi
    async deleteDivision(id) {
      if (!confirm('Apakah Anda yakin ingin menghapus divisi ini?')) {
        return;
      }

      this.loading = true;
      try {
        const response = await axios.delete(`/api/division/${id}`);
        this.showMessage(response.data.message, 'success');
        this.fetchDivisions();
      } catch (error) {
        const message = error.response?.data?.message || 'Gagal menghapus divisi';
        this.showMessage(message, 'error');
        console.error('Error deleting division:', error);
        this.loading = false;
      }
    },

    // Reset form
    resetForm() {
      this.form = {
        nama_divisi: '',
        deskripsi: '',
      };
      this.isEditing = false;
      this.currentId = null;
    },

    // Show message
    showMessage(text, type = 'success') {
      this.message = text;
      this.messageType = type;
      setTimeout(() => {
        this.message = '';
      }, 3000);
    },
  },
};
</script> 