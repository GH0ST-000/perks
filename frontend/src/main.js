import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import store from './store'

// Create app instance
const app = createApp(App)

// Use plugins
app.use(router)
app.use(store)

// Check authentication status on app start
store.dispatch('auth/checkAuth')

// Mount app
app.mount('#app')
