<template>
  <div class="flex h-screen bg-gray-100">
    <!-- Sidebar -->
    <div class="hidden md:flex md:flex-shrink-0 ">
      <div class="flex flex-col w-64 bg-gray-200">
        <!-- Sidebar Header -->
        <div class="h-16 flex items-center justify-center bg-gray-200">
          <h1 class="text-xl font-semibold text-gray-800">Admin Panel</h1>
        </div>

        <!-- Sidebar Navigation -->
        <nav class="flex-1 overflow-y-auto">
          <div class="px-2 py-4 space-y-1">
            <!-- Dashboard -->
            <router-link
              to="/admin"
              class="group flex items-center px-4 py-3 text-sm font-medium rounded-md"
              :class="[$route.path === '/admin' ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50']"
            >
              <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
              </svg>
              Dashboard
            </router-link>

            <!-- Users -->
            <router-link
              to="/admin/users"
              class="group flex items-center px-4 py-3 text-sm font-medium rounded-md"
              :class="[$route.path.includes('/admin/users') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50']"
            >
              <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              Users
            </router-link>

            <!-- Settings -->
            <router-link
              to="/admin/settings"
              class="group flex items-center px-4 py-3 text-sm font-medium rounded-md"
              :class="[$route.path.includes('/admin/settings') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50']"
            >
              <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Settings
            </router-link>
          </div>
        </nav>

        <!-- Sidebar Footer -->
        <div class="bg-gray-100 p-4">
          <button
            @click="logout"
            class="w-full flex items-center px-4 py-2 text-sm font-medium text-red-600 rounded-md hover:bg-red-50"
          >
            <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Logout
          </button>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col flex-1 overflow-hidden">

      <!-- Top Navigation -->
      <header class="bg-white shadow-sm">
        <div class="flex items-center justify-between h-16 px-6">
          <!-- Mobile Menu Button -->
          <button
            @click="isSidebarOpen = !isSidebarOpen"
            class="md:hidden text-gray-500 focus:outline-none"
          >
            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Search -->
          <div class="flex-1 px-4 flex justify-center lg:justify-end">
            <div class="max-w-lg w-full lg:max-w-xs">
              <label for="search" class="sr-only">Search</label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                  <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                  </svg>
                </div>
                <input
                  id="search"
                  class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                  placeholder="Search"
                  type="search"
                >
              </div>
            </div>
          </div>

          <!-- User Menu -->
          <div class="ml-4 flex items-center md:ml-6">
            <div class="relative">
              <button
                @click="isUserMenuOpen = !isUserMenuOpen"
                class="flex items-center max-w-xs text-sm rounded-full focus:outline-none"
              >
                <span class="mr-2 text-gray-700">{{ user.name || 'Admin User' }}</span>
                <img
                  class="h-8 w-8 rounded-full"
                  src="https://ui-avatars.com/api/?name=Admin+User&background=0095e8&color=fff"
                  alt="User avatar"
                >
              </button>

              <!-- User Dropdown Menu -->
              <div
                v-if="isUserMenuOpen"
                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
              >
                <router-link
                  to="/admin/profile"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Your Profile
                </router-link>
                <router-link
                  to="/admin/settings"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Settings
                </router-link>
                <button
                  @click="logout"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                >
                  Sign out
                </button>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Mobile Sidebar -->
      <div
        v-if="isSidebarOpen"
        class="fixed inset-0 flex z-40 md:hidden"
      >
        <!-- Overlay -->
        <div
          @click="isSidebarOpen = false"
          class="fixed inset-0 bg-gray-600 bg-opacity-75"
        ></div>

        <!-- Sidebar -->
        <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
          <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button
              @click="isSidebarOpen = false"
              class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
            >
              <span class="sr-only">Close sidebar</span>
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Mobile Sidebar Content -->
          <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
            <div class="flex-shrink-0 flex items-center px-4">
              <h1 class="text-xl font-semibold text-gray-800">Admin Panel</h1>
            </div>
            <nav class="mt-5 px-2 space-y-1">
              <!-- Dashboard -->
              <router-link
                to="/admin"
                class="group flex items-center px-4 py-3 text-sm font-medium rounded-md"
                :class="[$route.path === '/admin' ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50']"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
              </router-link>

              <!-- Users -->
              <router-link
                to="/admin/users"
                class="group flex items-center px-4 py-3 text-sm font-medium rounded-md"
                :class="[$route.path.includes('/admin/users') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50']"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Users
              </router-link>

              <!-- Settings -->
              <router-link
                to="/admin/settings"
                class="group flex items-center px-4 py-3 text-sm font-medium rounded-md"
                :class="[$route.path.includes('/admin/settings') ? 'bg-primary-50 text-primary-600' : 'text-gray-700 hover:bg-gray-50']"
              >
                <svg class="mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
              </router-link>
            </nav>
          </div>

          <!-- Mobile Sidebar Footer -->
          <div class="flex-shrink-0 flex bg-gray-100 p-4">
            <button
              @click="logout"
              class="flex-shrink-0 group block"
            >
              <div class="flex items-center">
                <div>
                  <img
                    class="inline-block h-10 w-10 rounded-full"
                    src="https://ui-avatars.com/api/?name=Admin+User&background=0095e8&color=fff"
                    alt="User avatar"
                  >
                </div>
                <div class="ml-3">
                  <p class="text-base font-medium text-gray-700 group-hover:text-gray-900">
                    {{ user.name || 'Admin User' }}
                  </p>
                  <p class="text-sm font-medium text-red-500 group-hover:text-red-700">
                    Sign out
                  </p>
                </div>
              </div>
            </button>
          </div>
        </div>
      </div>

      <!-- Page Content -->
      <main class="flex-1 relative overflow-y-auto focus:outline-none">
        <div class="py-6">
          <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
            <!-- Page Content -->
            <router-view/>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { useStore } from 'vuex';
import { useRoute } from 'vue-router';

const store = useStore();
const route = useRoute();
const isSidebarOpen = ref(false);
const isUserMenuOpen = ref(false);

// Get user data from store
const user = computed(() => store.getters['auth/currentUser']);

// Logout function using Vuex action
const logout = () => {
  store.dispatch('auth/logout');
};

// Data fetching is now handled by individual components when they are mounted
</script>

<style scoped>
/* Add custom styles for primary colors */
.bg-primary-50 {
  background-color: rgba(0, 158, 247, 0.05);
}

.text-primary-600 {
  color: var(--color-primary-600);
}

.focus\:ring-primary-500:focus {
  --tw-ring-color: var(--color-primary-500);
}

.focus\:border-primary-500:focus {
  border-color: var(--color-primary-500);
}
</style>
