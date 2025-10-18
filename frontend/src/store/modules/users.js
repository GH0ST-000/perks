import Cookies from 'js-cookie';

// Initial state
const state = {
  users: [],
  loading: false,
  error: null,
  currentUser: null
};

// Getters
const getters = {
  allUsers: state => state.users,
  isLoading: state => state.loading,
  hasError: state => state.error !== null,
  errorMessage: state => state.error,
  currentUser: state => state.currentUser
};

// Mutations
const mutations = {
  SET_LOADING(state, status) {
    state.loading = status;
  },
  SET_ERROR(state, error) {
    state.error = error;
  },
  SET_USERS(state, users) {
    state.users = users;
  },
  SET_CURRENT_USER(state, user) {
    state.currentUser = user;
  },
  ADD_USER(state, user) {
    state.users.push(user);
  },
  UPDATE_USER(state, updatedUser) {
    const index = state.users.findIndex(user => user.id === updatedUser.id);
    if (index !== -1) {
      state.users.splice(index, 1, updatedUser);
    }
  },
  DELETE_USER(state, userId) {
    state.users = state.users.filter(user => user.id !== userId);
  }
};

// Actions
const actions = {
  // Fetch all users
  async fetchUsers({ commit, rootState }) {
    console.log('Users Store - fetchUsers action called');
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);

    try {
      const token = Cookies.get('token');
      console.log('Users Store - Fetching users with token:', token);

      if (!token) {
        console.error('Users Store - No token found in cookies');
        throw new Error('No authentication token found');
      }

      // Check if we're authenticated according to the auth store
      const isAuthenticated = rootState.auth?.token;
      console.log('Users Store - Is authenticated according to auth store:', !!isAuthenticated);

      console.log('Users Store - Making API call to /api/admin/users');
      try {
        const response = await fetch('/api/admin/users', {
          method: 'GET',
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });

        console.log('Users Store - Response status:', response.status);
        console.log('Users Store - Response headers:', JSON.stringify(Array.from(response.headers.entries())));

        if (!response.ok) {
          console.error('Users Store - API call failed with status:', response.status);
          const errorText = await response.text();
          console.error('Users Store - Error response:', errorText);
          throw new Error(`Failed to fetch users: ${response.status} ${errorText || 'No error details'}`);
        }

        const responseText = await response.text();
        console.log('Users Store - Response text:', responseText);

        if (!responseText) {
          console.error('Users Store - Empty response from API');
          throw new Error('Empty response from server');
        }

        try {
          const data = JSON.parse(responseText);
          console.log('Users Store - Parsed data:', data);

          if (!data.users) {
            console.error('Users Store - No users array in response data:', data);
            throw new Error('Invalid response format: missing users array');
          }

          commit('SET_USERS', data.users);
          return { success: true, data: data.users };
        } catch (parseError) {
          console.error('Users Store - Error parsing JSON:', parseError);
          console.error('Users Store - Raw response:', responseText);
          throw new Error('Error parsing server response');
        }
      } catch (fetchError) {
        console.error('Users Store - Fetch error:', fetchError);
        throw fetchError;
      }
    } catch (error) {
      console.error('Users Store - Error fetching users:', error);
      commit('SET_ERROR', error.message);
      return { success: false, message: error.message };
    } finally {
      commit('SET_LOADING', false);
    }
  },

  // Create a new user
  async createUser({ commit, rootState }, userData) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);

    try {
      const token = Cookies.get('token');

      const response = await fetch('/api/admin/users', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(userData)
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to create user');
      }

      const data = await response.json();
      commit('ADD_USER', data.user);

      return { success: true, data: data.user };
    } catch (error) {
      commit('SET_ERROR', error.message);
      return { success: false, message: error.message };
    } finally {
      commit('SET_LOADING', false);
    }
  },

  // Update an existing user
  async updateUser({ commit, rootState }, { userId, userData }) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);

    try {
      const token = Cookies.get('token');

      const response = await fetch(`/api/admin/users/${userId}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`
        },
        body: JSON.stringify(userData)
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to update user');
      }

      const data = await response.json();
      commit('UPDATE_USER', data.user);

      return { success: true, data: data.user };
    } catch (error) {
      commit('SET_ERROR', error.message);
      return { success: false, message: error.message };
    } finally {
      commit('SET_LOADING', false);
    }
  },

  // Delete a user
  async deleteUser({ commit, rootState }, userId) {
    commit('SET_LOADING', true);
    commit('SET_ERROR', null);

    try {
      const token = Cookies.get('token');

      const response = await fetch(`/api/admin/users/${userId}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`
        }
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to delete user');
      }

      commit('DELETE_USER', userId);

      return { success: true };
    } catch (error) {
      commit('SET_ERROR', error.message);
      return { success: false, message: error.message };
    } finally {
      commit('SET_LOADING', false);
    }
  },

  // Set current user for editing
  setCurrentUser({ commit }, user) {
    commit('SET_CURRENT_USER', user);
  },

  // Clear current user
  clearCurrentUser({ commit }) {
    commit('SET_CURRENT_USER', null);
  }
};

export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
