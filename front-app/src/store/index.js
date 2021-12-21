import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    systemBarAndNavigation: false,
    isMobile: false,
    isLogged: localStorage.loggedUser ? true : false,
    activeEditItem: [],
    seacrhedBooks: [],
    loggedUser: localStorage.loggedUser ? JSON.parse(localStorage.getItem("loggedUser")) : [],
  },
  getters: {
    getSystemBarAndNavigation: (state) => state.systemBarAndNavigation,
    getSystemBreakepointApp: (state) => state.isMobile,
    getSystemLoggedStatus: (state) => state.isLogged,
    getSystemActiveEditItem: (state) => state.activeEditItem,
    getSystemSearchedBooks: (state) => state.seacrhedBooks,
    getSystemLoggedUser: (state) => state.loggedUser,
  },
  actions: {
    systemBarAndNavigation({ commit }, status) {
      commit("setSystemBarAndNavigation", status);
    },
    systemBreakepointApp({ commit }, status) {
      commit("setSystemBreakepointApp", status);
    },
    systemLoggedStatus({ commit }, status) {
      commit("setSystemLoggedStatus", status);
    },
    systemActiveEditItem({ commit }, status) {
      commit("setSystemActiveEditItem", status);
    },
    systemSearchedBooks({ commit }, status) {
      commit("setSystemSearchedBooks", status);
    },
    systemLoggedUser({ commit }, status) {
      commit("setSystemLoggedUser", status);
    },
  },

  mutations: {
    setSystemBarAndNavigation(state, status) {
      state.systemBarAndNavigation = status;
    },
    setSystemBreakepointApp(state, status) {
      state.isMobile = status;
    },
    setSystemLoggedStatus(state, status) {
      state.isLogged = status;
    },
    setSystemActiveEditItem(state, status) {
      state.activeEditItem = status;
    },
    setSystemSearchedBooks(state, status) {
      state.seacrhedBooks = status;
    },
    setSystemLoggedUser(state, status) {
      state.loggedUser = status;
      localStorage.loggedUser = JSON.stringify(status);
    },
  },

  modules: {},
});
