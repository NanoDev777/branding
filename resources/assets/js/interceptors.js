import axios from 'axios'
import store from './store'
import router from './router'
/**
 * Request
 */

axios.interceptors.request.use(
  (config) => {

    
    /*if (router.options.routes[1].path != '/login') {
      NProgress.start()
    }*/
    
    var token = localStorage.getItem('token')

    if (token) {
      config.headers['Authorization'] = 'Bearer ' + token
    }

    config.headers['X-Requested-With'] = 'XMLHttpRequest'
    config.headers['Accept-Language'] = 'en'

    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

/**
 * Response
 */
axios.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    const originalRequest = error.config

    // token expired
    if (error.response.status === 401 && error.response.data.error == "token_expired") {
      store.dispatch('logout')
      router.push({ name: 'login' })
    }

    return Promise.reject(error)
  }
)