import axios from 'axios'

const state = {
  user: null,
  authenticated: false,
  token: null
}

const mutations = {
  SAVE_TOKEN(state, data) {
    localStorage.setItem('token', data)
    state.token = data
    state.authenticated = true
  },

  SET_CURRENT_USER(state, data) {
    state.user = data
  },

  LOGOUT(state) {
    localStorage.removeItem('token')
    state.token = null
    state.authenticated = false
    state.user = null
  }
}

const actions = {
  saveToken({commit}, token) {
    commit('SAVE_TOKEN', token)
  },

  setCurrentUser({commit}, user) {
    commit('SET_CURRENT_USER', user)
  },

  async logout ({ commit }) {
    try {
      await axios.post('/api/logout')
    } catch (e) { }

    commit('LOGOUT')
  },

  refreshToken({commit}) {
    return new Promise((resolve, reject) => {
      axios.post('/api/refresh-token')
      .then(function (response) {
        resolve(response)
      })
      .catch(function (error) {
        reject(error)
      });
    }, error => console.log(error))
  }
}

const getters = {
  currentUser: (state) => {
    return state.user
  },

  authenticated: (state) => {
    return state.authenticated
  }
}


export default {
  state,
  mutations,
  actions,
  getters
}
