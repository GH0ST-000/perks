import { createRouter, createWebHistory } from 'vue-router';
import store from '../store';

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('../views/Login.vue'),
    meta: { requiresAuth: false }
  },
  {
    path: '/admin',
    component: () => import('../layouts/AdminLayout.vue'),
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      {
        path: '',
        name: 'admin',
        component: () => import('../views/admin/Dashboard.vue')
      },
      {
        path: 'profile',
        name: 'admin-profile',
        component: () => import('../views/admin/Dashboard.vue') // Placeholder, replace with actual profile component
      },
      {
        path: 'settings',
        name: 'admin-settings',
        component: () => import('../views/admin/Settings.vue')
      },
      {
        path: 'users',
        name: 'admin-users',
        component: () => import('../views/admin/UserList.vue')
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/login'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Navigation guard to check authentication and role
router.beforeEach((to, from, next) => {
  const isAuthenticated = store.getters['auth/isAuthenticated'];
  const isAdmin = store.getters['auth/isAdmin'];

  if (to.meta.requiresAuth && !isAuthenticated) {
    // If route requires auth and user is not logged in, redirect to login
    next({ name: 'login' });
  } else if (to.meta.requiresAdmin && !isAdmin) {
    // If route requires admin role and user is not admin, redirect to login
    next({ name: 'login' });
  } else {
    // Otherwise proceed
    next();
  }
});

export default router;
