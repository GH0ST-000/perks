import Cookies from 'js-cookie';

// Initial state
const state = {
  stats: {
    users: 0,
    products: 0,
    orders: 0,
    revenue: 0
  },
  recentActivities: [],
  loading: false,
  error: null
};

// Getters
const getters = {
  dashboardStats: state => state.stats,
  recentActivities: state => state.recentActivities,
  isLoading: state => state.loading,
  hasError: state => state.error !== null,
  errorMessage: state => state.error
};

// Mutations
const mutations = {
  SET_LOADING(state, status) {
    state.loading = status;
  },
  SET_ERROR(state, error) {
    state.error = error;
  },
  SET_DASHBOARD_DATA(state, { stats, recentActivities }) {
    state.stats = stats;
    state.recentActivities = recentActivities;
  }
};

// Actions
const actions = {
  // Fetch dashboard data
  async fetchDashboardData({ commit }) {
    console.log('Dashboard Store - fetchDashboardData action called');
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);

    try {
      const token = Cookies.get('token');
      console.log('Dashboard Store - Fetching dashboard data with token:', token);

      if (!token) {
        console.error('Dashboard Store - No token found in cookies');
        throw new Error('No authentication token found');
      }

      console.log('Dashboard Store - Making API call to /api/admin/dashboard');
      const response = await fetch('/api/admin/dashboard', {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        }
      });

      console.log('Dashboard Store - Response status:', response.status);
      console.log('Dashboard Store - Response headers:', JSON.stringify(Array.from(response.headers.entries())));

      if (!response.ok) {
        console.error('Dashboard Store - API call failed with status:', response.status);
        const errorText = await response.text();
        console.error('Dashboard Store - Error response:', errorText);
        throw new Error(`Failed to fetch dashboard data: ${response.status} ${errorText || 'No error details'}`);
      }

      const responseText = await response.text();
      console.log('Dashboard Store - Response text:', responseText);

      if (!responseText) {
        console.error('Dashboard Store - Empty response from API');
        throw new Error('Empty response from server');
      }

      try {
        const data = JSON.parse(responseText);
        console.log('Dashboard Store - Parsed data:', data);

        commit('SET_DASHBOARD_DATA', {
          stats: data.stats || {
            users: 0,
            products: 0,
            orders: 0,
            revenue: 0
          },
          recentActivities: data.recentActivities || []
        });

        return { success: true, data };
      } catch (parseError) {
        console.error('Dashboard Store - Error parsing JSON:', parseError);
        console.error('Dashboard Store - Raw response:', responseText);
        throw new Error('Error parsing server response');
      }
    } catch (error) {
      console.error('Dashboard Store - Error fetching dashboard data:', error);
      commit('SET_ERROR', error.message);
      return { success: false, message: error.message };
    } finally {
      commit('SET_LOADING', false);
    }
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
