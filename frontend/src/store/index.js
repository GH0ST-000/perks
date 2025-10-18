import { createStore } from 'vuex';
import auth from './modules/auth';
import users from './modules/users';
import dashboard from './modules/dashboard';

export default createStore({
  modules: {
    auth,
    users,
    dashboard
  }
});
