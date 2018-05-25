import axios from 'axios'
import Vue from 'vue'

const state = {
  amounts: [],
  packing: null
}

const mutations = {
  GET_AMOUNTS(state, amounts) {
    state.amounts = amounts.reverse()
  },

  ADD_AMOUNT(state, amount) {
    state.amounts.unshift(amount)
  },

  UPDATE_AMOUNT(state, amount) {
    const index = state.amounts.findIndex(x => x.id == amount.id)
    Vue.set(state.amounts, index, amount)
  },

  DELETE_AMOUNT(state, amount) {
    const index = state.amounts.findIndex(x => x.id == amount)
    state.amounts.splice(index, 1)
  },

  GET_PACKING(state, packing) {
    state.packing = packing
  },
}

const actions = {
  getAmounts({commit}, amounts) {
    return new Promise((resolve, reject) => {
      commit('GET_AMOUNTS', amounts)
      resolve()
    }, error => console.log(error))
  },

  addAmount({commit}, amount) {
    return new Promise((resolve, reject) => {
      commit('ADD_AMOUNT', amount)
      resolve()
    }, error => console.log(error))
  },

  updateAmount({commit}, amount) {
    return new Promise((resolve, reject) => {
      commit('UPDATE_AMOUNT', amount)
      resolve()
    }, error => console.log(error))
  },

  deleteAmount({commit}, amount) {
    return new Promise((resolve, reject) => {
      axios.delete('/api/amount/'+amount)
      .then((response) => {
        commit('DELETE_AMOUNT', amount)
        resolve(response)
      })
    }, error => console.log(error))
  },

  getPacking({commit}, packing) {
    return new Promise((resolve, reject) => {
      commit('GET_PACKING', packing)
      resolve()
    }, error => console.log(error))
  }

}

const getters = {
  amounts: (state) => {
    return state.amounts
  },
  packing: (state) => {
    return state.packing
  }
}


export default {
  state,
  mutations,
  actions,
  getters
}

