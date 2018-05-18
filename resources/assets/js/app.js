import './bootstrap'
import Vue from 'vue'
import Vuetify from 'vuetify'
import Snotify from 'vue-snotify'
import VeeValidate from 'vee-validate'
import App from './App.vue'
import router from './router'
import store from './store'
import AmCharts from 'amcharts3'
import AmSerial from 'amcharts3/amcharts/serial'


const options = {
  toast: {
    timeout: 6000,
    showProgressBar: true,
    closeOnClick: true,
    pauseOnHover: true,
    icon: '/img/icon.png'
  }
}

Vue.use(Snotify, options)
Vue.use(Vuetify)
Vue.use(VeeValidate)
Vue.filter('formatDate', require('./filters/formatDate'));

const app = new Vue({
    el: '#app',
    router,
    store,
    render: h => h(App)
});
