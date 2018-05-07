import axios from 'axios'
import Vue from 'vue'

const state = {
  categories: []
}

const mutations = {
  GET_CATEGORIES(state, categories) {
    state.categories = categories.reverse()
  },

  ADD_CATEGORY(state, category) {
    state.categories.unshift(category)
  },

  UPDATE_CATEGORY(state, category) {
    const index = state.categories.findIndex(x => x.id == category.id)
    Vue.set(state.categories, index, category)
  },

  DELETE_CATEGORY(state, category) {
    const index = state.categories.findIndex(x => x.id == category.id)
    state.categories.splice(index, 1)
  }
}

const actions = {
  getCategories({commit}) {
    return new Promise((resolve, reject) => {
      axios.get('/api/categories')
      .then((response) => {
        commit('GET_CATEGORIES', response.data.data)
        resolve()
      })
      .catch((error) => {
        reject(error)
      });
    }, error => console.log(error))
  },

  addCategory({commit}, category) {
    return new Promise((resolve, reject) => {
      commit('ADD_CATEGORY', category)
      resolve()
    }, error => console.log(error))
  },

  updateCategory({commit}, category) {
    return new Promise((resolve, reject) => {
      commit('UPDATE_CATEGORY', category)
      resolve()
    }, error => console.log(error))
  },

  deleteCategory({commit}, category) {
    return new Promise((resolve, reject) => {
      axios.delete('/api/category/'+category.id)
      .then((response) => {
        commit('DELETE_CATEGORY', category)
        resolve(response)
      })
      .catch((error) => {
        reject('Intentelo de nuevo más tarde')
      });
    }, error => console.log(error))
  }
}

const getters = {
  categories: (state) => {
    return state.categories
  }
}


export default {
  state,
  mutations,
  actions,
  getters
}
