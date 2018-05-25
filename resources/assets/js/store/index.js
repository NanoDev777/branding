import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from './modules/auth'
import products from './modules/products'
import categories from './modules/categories'
import colors from './modules/colors'
import quotations from './modules/quotations'
import prices from './modules/prices'
import message from './modules/message'

Vue.use(Vuex)

export default new Vuex.Store({
  modules:{
    auth,
    products,
    colors,
    message
  },
  plugins: [createPersistedState()]
})
