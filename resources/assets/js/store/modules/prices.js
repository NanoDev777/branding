import axios from 'axios'
import Vue from 'vue'

const state = {
  prices: []
}

const mutations = {
  GET_PRICES(state, prices) {
    state.prices = prices.reverse()
  },

  ADD_PRICE(state, price) {
    state.prices.unshift(price)
  },

  UPDATE_PRICE(state, price) {
    const index = state.prices.findIndex(x => x.id == price.id)
    Vue.set(state.prices, index, price)
  },

  DELETE_PRICE(state, price) {
    const index = state.prices.findIndex(x => x.id == price.id)
    state.prices.splice(index, 1)
  }
}

const actions = {
  getPrices({commit}) {
    return new Promise((resolve, reject) => {
      axios.get('/api/prices')
      .then((response) => {
        commit('GET_PRICES', response.data.data)
        resolve()
      })
      .catch((error) => {
        console.log(error);
      });
    }, error => console.log(error))
  },

  addPrice({commit}, price) {
    return new Promise((resolve, reject) => {
      commit('ADD_PRICE', price)
      resolve()
    }, error => console.log(error))
  },

  updatePrice({commit}, price) {
    return new Promise((resolve, reject) => {
      commit('UPDATE_PRICE', price)
      resolve()
    }, error => console.log(error))
  },

  deletePrice({commit}, price) {
    return new Promise((resolve, reject) => {
      axios.delete('/api/price/'+price.id)
      .then((response) => {
        commit('DELETE_PRICE', price)
        resolve(response)
      })
      .catch((error) => {
        console.log(error);
      });
    }, error => console.log(error))
  }
}

const getters = {
  prices: (state) => {
    return state.prices
  }
}


export default {
  state,
  mutations,
  actions,
  getters
}
