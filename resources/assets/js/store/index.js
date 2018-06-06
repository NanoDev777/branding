import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
import auth from './modules/auth'
import colors from './modules/colors'
import message from './modules/message'

Vue.use(Vuex)

export default new Vuex.Store({
  modules:{
    auth,
    colors,
    message
  },
  plugins: [createPersistedState()]
})
