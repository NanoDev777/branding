import axios from 'axios'

const state = {
  products: [],
  total: 0
}

const mutations = {
  ADD_PRODUCT(state, product) {
    const index = state.products.findIndex(x => x.id == product.id)
    if (index > -1) {
      return false
    } else{
      state.products.unshift(product)
      state.total++
    }
  },

  DELETE_PRODUCT(state, product) {
    const index = state.products.findIndex(x => x.id == product)
    if (index > -1) {
      state.products.splice(index, 1)
      state.total--
    } else{
      return false
    }
  },

  EMPTY_PRODUCTS(state){
    state.products = []
    state.total = 0
  }
}

const actions = {
  addProduct({commit}, product) {
    return new Promise((resolve, reject) => {
      commit('ADD_PRODUCT', product)
      resolve()
    }, error => console.log(error))
  },

  deleteProduct({commit}, product) {
    return new Promise((resolve, reject) => {
      commit('DELETE_PRODUCT', product)
      resolve()
    }, error => console.log(error))
  },

  emptyProducts({commit}) {
    return new Promise((resolve, reject) => {
      commit('EMPTY_PRODUCTS')
      resolve()
    }, error => console.log(error))
  }
}

const getters = {
  products: (state) => {
    return state.products
  },

  total: (state) => {
    return state.total
  }
}


export default {
  state,
  mutations,
  actions,
  getters
}
