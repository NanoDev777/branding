import axios from 'axios'

const state = {
  colors: []
}

const mutations = {
  GET_COLORS(state, colors) {
    state.colors = colors.reverse()
  },

  ADD_COLOR(state, color) {
    state.colors.unshift(color)
  },

  DELETE_COLOR(state, color) {
    state.colors.splice(color, 1)
  }
}

const actions = {
  getColors({commit}) {
    return new Promise((resolve, reject) => {
      axios.get('/api/colors')
      .then((response) => {
        commit('GET_COLORS', response.data.data)
        resolve()
      })
      .catch((error) => {
        reject('Intentelo de nuevo más tarde')
      });
    }, error => console.log(error))
  },

  addColor({commit}, color) {
    return new Promise((resolve, reject) => {
      commit('ADD_COLOR', color)
      resolve()
    }, error => console.log(error))
  }
}

const getters = {
  colors: (state) => {
    return state.colors
  }
}


export default {
  state,
  mutations,
  actions,
  getters
}
