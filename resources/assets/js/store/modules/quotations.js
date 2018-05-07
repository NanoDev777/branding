import axios from 'axios'

const state = {
  quotations: []
}

const mutations = {
  GET_QUOTATIONS(state, quotations) {
    state.quotations = quotations.reverse()
  },

  ADD_QUOTATION(state, quotation) {
    quotation.forEach((e) => {
      state.quotations.push(e)
    })
  },

  DELETE_QUOTATIONS(state, quotations) {
    const newObj = state.quotations.filter(v => !quotations.find(x => x === v.id))
    state.quotations = newObj
  }
}

const actions = {
  getQuotations({commit}, data) {
    return new Promise((resolve, reject) => {
      axios.post('/api/select-product', data)
      .then((response) => {
        commit('GET_QUOTATIONS', response.data.data)
        resolve()
      })
      .catch((error) => {
        reject(error)
      })
    }, error => console.log(error))
  },

  addQuotation({commit}, quotation) {
    return new Promise((resolve, reject) => {
      commit('ADD_QUOTATION', quotation)
      resolve()
    }, error => console.log(error))
  },

  deleteQuotations({commit}, quotations) {
    return new Promise((resolve, reject) => {
      commit('DELETE_QUOTATIONS', quotations)
      resolve()
    }, error => console.log(error))
  }
}

const getters = {
  quotations: (state) => {
    return state.quotations
  }
}


export default {
  state,
  mutations,
  actions,
  getters
}
