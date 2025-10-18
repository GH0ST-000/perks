<template>
  <div>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">Users Management</h1>
      <button
        @click="openCreateModal"
        class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
      >
        Add New User
      </button>
    </div>

    <!-- Alert Messages -->
    <div v-if="successMessage" class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 rounded-md">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-green-700">{{ successMessage }}</p>
        </div>
      </div>
    </div>

    <div v-if="errorMessage" class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 rounded-md">
      <div class="flex">
        <div class="flex-shrink-0">
          <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm text-red-700">{{ errorMessage }}</p>
        </div>
      </div>
    </div>

    <!-- Loading Indicator -->
    <div v-if="isLoading || !dataLoaded" class="flex justify-center my-8">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-600"></div>
    </div>

    <!-- Users Table -->
    <div v-else-if="users.length > 0" class="bg-white shadow overflow-hidden sm:rounded-md">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="user in users" :key="user.id">
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10">
                  <img
                    class="h-10 w-10 rounded-full"
                    :src="user.profile_photo || `https://ui-avatars.com/api/?name=${encodeURIComponent(user.name)}&background=0095e8&color=fff`"
                    alt="User avatar"
                  >
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                </div>
              </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <div class="text-sm text-gray-900">{{ user.email }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
              <span
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                :class="user.role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'"
              >
                {{ user.role }}
              </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
              {{ formatDate(user.created_at) }}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <button
                @click="openEditModal(user)"
                class="text-primary-600 hover:text-primary-900 mr-3"
              >
                Edit
              </button>
              <button
                @click="confirmDelete(user)"
                class="text-red-600 hover:text-red-900"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Empty State -->
    <div v-else class="bg-white shadow overflow-hidden sm:rounded-md p-6 text-center">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">No users found</h3>
      <p class="mt-1 text-sm text-gray-500">Get started by creating a new user.</p>
      <div class="mt-6">
        <button
          @click="openCreateModal"
          class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
        >
          <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
          </svg>
          Add User
        </button>
      </div>
    </div>

    <!-- Create/Edit User Modal -->
    <div v-if="showModal" class="fixed z-40 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  {{ isEditing ? 'Edit User' : 'Create New User' }}
                </h3>
                <div class="mt-4">
                  <form @submit.prevent="submitForm">
                    <!-- Name Field -->
                    <div class="mb-4">
                      <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                      <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                      >
                      <p v-if="formErrors.name" class="mt-1 text-sm text-red-600">{{ formErrors.name }}</p>
                    </div>

                    <!-- Email Field -->
                    <div class="mb-4">
                      <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                      <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                      >
                      <p v-if="formErrors.email" class="mt-1 text-sm text-red-600">{{ formErrors.email }}</p>
                    </div>

                    <!-- Password Field (only for new users) -->
                    <div v-if="!isEditing" class="mb-4">
                      <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                      <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                      >
                      <p v-if="formErrors.password" class="mt-1 text-sm text-red-600">{{ formErrors.password }}</p>
                    </div>

                    <!-- Role Field -->
                    <div class="mb-4">
                      <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                      <select
                        id="role"
                        v-model="form.role"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                      >
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                      </select>
                      <p v-if="formErrors.role" class="mt-1 text-sm text-red-600">{{ formErrors.role }}</p>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              @click="submitForm"
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm"
              :disabled="formSubmitting"
            >
              {{ formSubmitting ? 'Processing...' : (isEditing ? 'Update' : 'Create') }}
            </button>
            <button
              @click="closeModal"
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed z-40 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Delete User
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Are you sure you want to delete this user? This action cannot be undone.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              @click="deleteUser"
              type="button"
              class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
              :disabled="formSubmitting"
            >
              {{ formSubmitting ? 'Processing...' : 'Delete' }}
            </button>
            <button
              @click="closeDeleteModal"
              type="button"
              class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useStore } from 'vuex';
import AdminLayout from '../../layouts/AdminLayout.vue';

const store = useStore();

// State
const showModal = ref(false);
const showDeleteModal = ref(false);
const isEditing = ref(false);
const formSubmitting = ref(false);
const userToDelete = ref(null);
const successMessage = ref('');
const errorMessage = ref('');
const formErrors = ref({});

// Form data
const form = ref({
  name: '',
  email: '',
  password: '',
  role: 'user'
});

// Get users from store
const users = computed(() => {
  const usersList = store.getters['users/allUsers'];
  console.log('UserList - users from store:', usersList);
  return usersList;
});
const isLoading = computed(() => store.getters['users/isLoading']);

// Check if data is loaded
const dataLoaded = computed(() => {
  const usersLoaded = Array.isArray(users.value) && users.value.length > 0;
  console.log('UserList - dataLoaded:', { usersLoaded });
  return usersLoaded;
});

// Fetch users on component mount
onMounted(async () => {
  console.log('UserList - Component mounted');
  clearMessages();

  try {
    console.log('UserList - Dispatching users/fetchUsers action');
    const result = await store.dispatch('users/fetchUsers');
    console.log('UserList - Dispatch result:', result);

    if (!result.success) {
      console.error('UserList - Error fetching users:', result.message);
    }
  } catch (error) {
    console.error('UserList - Error in onMounted:', error);
  }
});

// Format date
const formatDate = (dateString) => {
  if (!dateString) return '';
  const date = new Date(dateString);
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

// Clear messages
const clearMessages = () => {
  successMessage.value = '';
  errorMessage.value = '';
  formErrors.value = {};
};

// Open create modal
const openCreateModal = () => {
  clearMessages();
  isEditing.value = false;
  form.value = {
    name: '',
    email: '',
    password: '',
    role: 'user'
  };
  showModal.value = true;
};

// Open edit modal
const openEditModal = (user) => {
  clearMessages();
  isEditing.value = true;
  form.value = {
    id: user.id,
    name: user.name,
    email: user.email,
    role: user.role
  };
  showModal.value = true;
};

// Close modal
const closeModal = () => {
  showModal.value = false;
};

// Submit form
const submitForm = async () => {
  clearMessages();
  formSubmitting.value = true;

  try {
    // Validate form
    if (!form.value.name) {
      formErrors.value.name = 'Name is required';
    }
    if (!form.value.email) {
      formErrors.value.email = 'Email is required';
    }
    if (!isEditing.value && !form.value.password) {
      formErrors.value.password = 'Password is required';
    }

    // If there are validation errors, stop
    if (Object.keys(formErrors.value).length > 0) {
      formSubmitting.value = false;
      return;
    }

    let result;

    if (isEditing.value) {
      // Update existing user
      result = await store.dispatch('users/updateUser', {
        userId: form.value.id,
        userData: {
          name: form.value.name,
          email: form.value.email,
          role: form.value.role
        }
      });

      if (result.success) {
        successMessage.value = 'User updated successfully';
        closeModal();
      } else {
        errorMessage.value = result.message || 'Failed to update user';
      }
    } else {
      // Create new user
      result = await store.dispatch('users/createUser', {
        name: form.value.name,
        email: form.value.email,
        password: form.value.password,
        role: form.value.role
      });

      if (result.success) {
        successMessage.value = 'User created successfully';
        closeModal();
      } else {
        errorMessage.value = result.message || 'Failed to create user';
      }
    }
  } catch (error) {
    errorMessage.value = error.message || 'An error occurred';
  } finally {
    formSubmitting.value = false;
  }
};

// Confirm delete
const confirmDelete = (user) => {
  clearMessages();
  userToDelete.value = user;
  showDeleteModal.value = true;
};

// Close delete modal
const closeDeleteModal = () => {
  showDeleteModal.value = false;
  userToDelete.value = null;
};

// Delete user
const deleteUser = async () => {
  if (!userToDelete.value) return;

  formSubmitting.value = true;

  try {
    const result = await store.dispatch('users/deleteUser', userToDelete.value.id);

    if (result.success) {
      successMessage.value = 'User deleted successfully';
      closeDeleteModal();
    } else {
      errorMessage.value = result.message || 'Failed to delete user';
    }
  } catch (error) {
    errorMessage.value = error.message || 'An error occurred';
  } finally {
    formSubmitting.value = false;
  }
};
</script>

<style scoped>
.bg-primary-600 {
  background-color: var(--color-primary-600);
}

.hover\:bg-primary-700:hover {
  background-color: var(--color-primary-700);
}

.focus\:ring-primary-500:focus {
  --tw-ring-color: var(--color-primary-500);
}

.focus\:border-primary-500:focus {
  border-color: var(--color-primary-500);
}

.text-primary-600 {
  color: var(--color-primary-600);
}

.hover\:text-primary-900:hover {
  color: var(--color-primary-700);
}
</style>
