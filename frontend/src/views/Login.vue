<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Left side with logo and background -->
    <div class="hidden lg:flex lg:w-1/2 bg-primary-600 flex-col justify-center items-center p-12 text-white">
      <div class="max-w-md">
        <h1 class="text-4xl font-bold mb-6">Welcome to Admin Panel</h1>
        <p class="text-lg mb-8">Please sign in to your account and start the adventure</p>
        <img src="https://keenthemes.com/metronic/assets/media/illustrations/sigma-1/17.png" alt="Login illustration" class="w-full max-w-sm mx-auto">
      </div>
    </div>

    <!-- Right side with login form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
      <div class="w-full max-w-md">
        <div class="text-center mb-10">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Sign In</h1>
          <p class="text-gray-600">Enter your credentials to access your account</p>
        </div>

        <!-- Login Form -->
        <form @submit.prevent="handleLogin" class="space-y-6">
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              id="email"
              v-model="email"
              type="email"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
              placeholder="Enter your email"
            >
            <p v-if="errors.email" class="mt-1 text-sm text-red-600">{{ errors.email }}</p>
          </div>

          <!-- Password Field -->
          <div>
            <div class="flex justify-between mb-1">
              <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
              <a href="#" class="text-sm text-primary-600 hover:text-primary-500">Forgot Password?</a>
            </div>
            <input
              id="password"
              v-model="password"
              type="password"
              required
              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500"
              placeholder="Enter your password"
            >
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">{{ errors.password }}</p>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <input
              id="remember"
              v-model="remember"
              type="checkbox"
              class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded"
            >
            <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
          </div>

          <!-- Login Button -->
          <div>
            <button
              type="submit"
              class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"
              :disabled="loading"
            >
              <span v-if="loading">Processing...</span>
              <span v-else>Sign In</span>
            </button>
          </div>

          <!-- Error Message -->
          <div v-if="errorMessage" class="p-4 bg-red-100 border-l-4 border-red-500 rounded-md">
            <div class="flex">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
              </div>
              <div class="ml-3">
                <p class="text-sm text-red-700">{{ errorMessage }}</p>
              </div>
            </div>
          </div>
        </form>

        <!-- Sign Up Link -->
        <div class="text-center mt-8">
          <p class="text-sm text-gray-600">
            Don't have an account?
            <a href="#" class="font-medium text-primary-600 hover:text-primary-500">Sign up</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useStore } from 'vuex';

const store = useStore();
const email = ref('');
const password = ref('');
const remember = ref(false);
const loading = ref(false);
const errorMessage = ref('');
const errors = ref({});

// Computed properties from store
const authStatus = computed(() => store.getters['auth/authStatus']);

const handleLogin = async () => {
  // Reset errors
  errors.value = {};
  errorMessage.value = '';
  loading.value = true;

  try {
    // Validate form
    if (!email.value) {
      errors.value.email = 'Email is required';
    }
    if (!password.value) {
      errors.value.password = 'Password is required';
    }

    // If there are validation errors, stop
    if (Object.keys(errors.value).length > 0) {
      loading.value = false;
      return;
    }

    // Use Vuex action to login
    const result = await store.dispatch('auth/login', {
      email: email.value,
      password: password.value
    });

    if (!result.success) {
      throw new Error(result.message || 'Login failed');
    }

    // No need to redirect here as it's handled in the store action
  } catch (error) {
    errorMessage.value = error.message || 'An error occurred during login';
  } finally {
    loading.value = false;
  }
};
</script>

<style>
/* Add custom styles for primary colors */
:root {
  --color-primary-500: #009ef7;
  --color-primary-600: #0095e8;
  --color-primary-700: #0078bd;
}

.bg-primary-600 {
  background-color: var(--color-primary-600);
}

.text-primary-500 {
  color: var(--color-primary-500);
}

.text-primary-600 {
  color: var(--color-primary-600);
}

.hover\:text-primary-500:hover {
  color: var(--color-primary-500);
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
</style>
