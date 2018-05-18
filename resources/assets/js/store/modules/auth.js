import axios from 'axios'

const state = {
  user: null,
  authenticated: false,
  token: null,
  expiration: null
}

const mutations = {
  SAVE_TOKEN(state, data) {
    localStorage.setItem('token', data)
    state.token = data
    state.authenticated = true
  },

  SAVE_EXPIRATION(state, expires) {
    state.expiration = expires
  },

  SET_CURRENT_USER(state, data) {
    state.user = data
  },

  LOGOUT(state) {
    localStorage.removeItem('token')
    state.token = null
    state.authenticated = false
    state.expiration = null
  }
}

const actions = {
  saveToken({commit}, token) {
    commit('SAVE_TOKEN', token)
  },

  saveExpiration({commit}, expires) {
    commit('SAVE_EXPIRATION', expires)
  },

  /*setCurrentUser({commit}, user) {
    commit('SET_CURRENT_USER', user)
  },*/

  getDataUser({commit}) {
    return new Promise((resolve, reject) => {
      axios.get('api/autenticado')
      .then((response) => {
        commit('SET_CURRENT_USER', response.data)
        resolve()
      })
      .catch((error) => {
        console.log(error);
      })
    }, error => console.log(error))
  },

  async logout ({ commit }, id) {
    try {
      await axios.post('/api/logout', { id: id })
    } catch (e) { }

    commit('LOGOUT')
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
