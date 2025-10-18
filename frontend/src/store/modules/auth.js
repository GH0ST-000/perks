import Cookies from 'js-cookie';
import router from '../../router';

// Initial state
const state = {
  token: Cookies.get('token') || '',
  user: JSON.parse(Cookies.get('user') || '{}'),
  status: ''
};

// Getters
const getters = {
  isAuthenticated: state => !!state.token,
  authStatus: state => state.status,
  currentUser: state => state.user,
  isAdmin: state => state.user && state.user.role === 'admin'
};

// Mutations
const mutations = {
  AUTH_REQUEST(state) {
    state.status = 'loading';
  },
  AUTH_SUCCESS(state, { token, user }) {
    state.status = 'success';
    state.token = token;
    state.user = user;
  },
  AUTH_ERROR(state) {
    state.status = 'error';
    state.token = '';
    state.user = {};
  },
  LOGOUT(state) {
    state.status = '';
    state.token = '';
    state.user = {};
  },
  SET_USER(state, user) {
    state.user = user;
  }
};

// Actions
const actions = {
  // Login action
  async login({ commit }, credentials) {
    commit('AUTH_REQUEST');
    try {
      // Make API request to login
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(credentials),
      });

      // Check if response is empty
      const responseText = await response.text();
      let data;

      try {
        // Only parse as JSON if there's content
        data = responseText ? JSON.parse(responseText) : {};
      } catch (e) {
        console.error('Error parsing JSON response:', e);
        throw new Error('Invalid response from server. Please try again later.');
      }

      if (!response.ok) {
        throw new Error(data.error || 'Login failed');
      }

      // Store token in cookie (7 days expiration)
      Cookies.set('token', data.access_token, { expires: 7 });

      // Get user data from the /api/auth/me endpoint
      const userResponse = await fetch('/api/auth/me', {
        headers: {
          'Authorization': `Bearer ${data.access_token}`
        }
      });

      if (!userResponse.ok) {
        throw new Error('Failed to fetch user data');
      }

      // Check if user response is empty
      const userResponseText = await userResponse.text();
      let userData;

      try {
        // Only parse as JSON if there's content
        userData = userResponseText ? JSON.parse(userResponseText) : {};
      } catch (e) {
        console.error('Error parsing user JSON response:', e);
        throw new Error('Invalid user data response from server. Please try again later.');
      }

      // Store user data in cookie (7 days expiration)
      Cookies.set('user', JSON.stringify(userData || {}), { expires: 7 });

      // Update state
      commit('AUTH_SUCCESS', {
        token: data.access_token,
        user: userData || {}
      });

      // Redirect based on role
      if (userData && userData.role === 'admin') {
        router.push('/admin');
      } else {
        router.push('/dashboard');
      }

      return { success: true };
    } catch (error) {
      commit('AUTH_ERROR');
      Cookies.remove('token');
      Cookies.remove('user');
      return { success: false, message: error.message };
    }
  },

  // Logout action
  async logout({ commit, state }) {
    try {
      if (state.token) {
        // Call logout API
        await fetch('/api/auth/logout', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${state.token}`
          }
        });
      }
    } catch (error) {
      console.error('Error during logout:', error);
    } finally {
      // Clear cookies and state regardless of API success
      Cookies.remove('token');
      Cookies.remove('user');
      commit('LOGOUT');
      router.push('/login');
    }
  },

  // Check auth status
  checkAuth({ commit, state }) {
    console.log('Auth Store - checkAuth action called');
    const token = Cookies.get('token');
    const user = Cookies.get('user');

    console.log('Auth Store - Token from cookie:', token ? 'exists' : 'not found');
    console.log('Auth Store - User from cookie:', user ? 'exists' : 'not found');

    if (token && user) {
      try {
        const userData = JSON.parse(user);
        console.log('Auth Store - Parsed user data:', userData);
        commit('AUTH_SUCCESS', { token, user: userData });
        console.log('Auth Store - Authentication successful');

        // Test if the token is valid by making a request to /api/auth/me
        console.log('Auth Store - Testing token validity with /api/auth/me');
        fetch('/api/auth/me', {
          headers: {
            'Authorization': `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        })
        .then(response => {
          console.log('Auth Store - /api/auth/me response status:', response.status);
          if (response.ok) {
            return response.text();
          } else {
            console.error('Auth Store - /api/auth/me failed with status:', response.status);
            throw new Error('Token validation failed');
          }
        })
        .then(text => {
          if (text) {
            try {
              const data = JSON.parse(text);
              console.log('Auth Store - /api/auth/me response data:', data);
            } catch (parseError) {
              console.error('Auth Store - Error parsing /api/auth/me response:', parseError);
            }
          } else {
            console.error('Auth Store - Empty response from /api/auth/me');
          }
        })
        .catch(error => {
          console.error('Auth Store - Error validating token:', error);
        });
      } catch (e) {
        console.error('Auth Store - Error parsing user data from cookie:', e);
        Cookies.remove('token');
        Cookies.remove('user');
        commit('LOGOUT');
        console.log('Auth Store - Logged out due to parsing error');
      }
    } else {
      commit('LOGOUT');
      console.log('Auth Store - Logged out due to missing token or user data');
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
