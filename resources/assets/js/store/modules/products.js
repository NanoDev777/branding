import axios from 'axios'

const state = {
  products: []
}

const mutations = {
  GET_PRODUCTS(state, products) {
    state.products = products.reverse()
  },

  ADD_PRODUCT(state, product) {
    state.products.unshift(product)
  },

  UPDATE_PRODUCT(state, product) {
    const foundIndex = state.products.findIndex(x => x.id == product.id)
    state.products[foundIndex] = product
  },

  DELETE_PRODUCT(state, product) {
    state.products.splice(product, 1)
  }
}

const actions = {
  getProducts({commit}) {
    return new Promise((resolve, reject) => {
      axios.get('/api/products')
      .then((response) => {
        commit('GET_PRODUCTS', response.data.data)
        resolve()
      })
      .catch((error) => {
        console.log(error);
      })
    }, error => console.log(error))
  },

  addProduct({commit}, product) {
    return new Promise((resolve, reject) => {
      commit('ADD_PRODUCT', product)
      resolve()
    }, error => console.log(error))
  },

  updateProduct({commit}, product) {
    return new Promise((resolve, reject) => {
      commit('UPDATE_PRODUCT', product)
      resolve()
    }, error => console.log(error))
  },

  deleteProduct({commit}, product) {
    return new Promise((resolve, reject) => {
      axios.delete('/api/notes/'+product)
      .then((response) => {
        commit('DELETE_PRODUCT', product)
        resolve(response)
      })
      .catch((error) => {
        reject(error.response.data.error)
      })
    }, error => console.log(error))
  }
}

const getters = {
  products: (state) => {
    return state.products
  }
}


export default {
  state,
  mutations,
  actions,
  getters
}

