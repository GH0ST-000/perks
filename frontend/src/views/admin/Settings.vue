<template>
  <div>
      <h1 class="text-2xl font-semibold text-gray-900">Settings</h1>

      <!-- Loading Indicator -->
      <div v-if="isLoading || !dataLoaded" class="flex justify-center my-8">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-600"></div>
      </div>

      <div v-else>
        <!-- Settings Content -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Profile Settings</h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
              <p>Manage your account settings and preferences.</p>
            </div>

            <!-- Profile Information -->
            <div class="mt-5 border-t border-gray-200 pt-5">
              <dl class="divide-y divide-gray-200">
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Full name</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.name }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Email address</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ user.email }}</dd>
                </div>
                <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                  <dt class="text-sm font-medium text-gray-500">Role</dt>
                  <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <span
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="user.role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'"
                    >
                      {{ user.role }}
                    </span>
                  </dd>
                </div>
              </dl>
            </div>

            <!-- Actions -->
            <div class="mt-5">
              <button
                @click="openEditProfileModal"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
              >
                Edit Profile
              </button>
              <button
                @click="openChangePasswordModal"
                class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
              >
                Change Password
              </button>
            </div>
          </div>
        </div>

        <!-- System Settings -->
        <div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
          <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">System Settings</h3>
            <div class="mt-2 max-w-xl text-sm text-gray-500">
              <p>Configure system-wide settings.</p>
            </div>

            <!-- Notifications -->
            <div class="mt-5">
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input
                    id="notifications"
                    v-model="notifications"
                    type="checkbox"
                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded"
                  >
                </div>
                <div class="ml-3 text-sm">
                  <label for="notifications" class="font-medium text-gray-700">Email notifications</label>
                  <p class="text-gray-500">Receive email notifications for important updates.</p>
                </div>
              </div>
            </div>

            <!-- Theme -->
            <div class="mt-5">
              <label for="theme" class="block text-sm font-medium text-gray-700">Theme</label>
              <select
                id="theme"
                v-model="theme"
                class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-md"
              >
                <option value="light">Light</option>
                <option value="dark">Dark</option>
                <option value="system">System</option>
              </select>
            </div>

            <!-- Save Button -->
            <div class="mt-5">
              <button
                @click="saveSettings"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
              >
                Save Settings
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
const isLoading = ref(true);
const notifications = ref(true);
const theme = ref('light');
const errorMessage = ref('');
const successMessage = ref('');

// Get user from store
const user = computed(() => {
  const userData = store.getters['auth/currentUser'];
  console.log('Settings - user from store:', userData);
  return userData;
});

// Check if data is loaded
const dataLoaded = computed(() => {
  const userLoaded = user.value && typeof user.value === 'object' && 'name' in user.value;
  console.log('Settings - dataLoaded:', { userLoaded });
  return userLoaded;
});

// Modal functions (placeholders)
const openEditProfileModal = () => {
  // Implementation would go here
  alert('Edit Profile functionality would open a modal here');
};

const openChangePasswordModal = () => {
  // Implementation would go here
  alert('Change Password functionality would open a modal here');
};

// Save settings function (placeholder)
const saveSettings = () => {
  // Implementation would go here
  successMessage.value = 'Settings saved successfully';
  setTimeout(() => {
    successMessage.value = '';
  }, 3000);
};

// Fetch user data on component mount
onMounted(async () => {
  console.log('Settings - Component mounted');
  try {
    // We already have the user data in the auth store, so we can just use that
    // If we needed to fetch additional data, we would do it here

    // Simulate API call delay
    setTimeout(() => {
      isLoading.value = false;
    }, 500);
  } catch (error) {
    console.error('Settings - Error fetching user data:', error);
    errorMessage.value = 'Failed to load settings. Please try again.';
  }
});
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
</style>
