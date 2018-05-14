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
    
    if (error.response.status >= 500) {
      store.dispatch('responseMessage', {
        type: 'error',
        text: error.response.data.error,
        title: 'Error',
        modal: true
      })
    }
    // token expired
    if (error.response.status === 401 && error.response.data.error == "token_expired") {
      store.dispatch('responseMessage', {
        type: 'warning',
        text: 'Por favór, vuelva a iniciar sesión para poder continuar...',
        title: 'Sesión Expirada!',
        modal: true
      })
      .then(async () => {
        await store.dispatch('logout')
        router.push({ name: 'login' })
      })


      /*originalRequest._retry = true

      store.dispatch('logout').then((response) => {
        // console.log(response)
        let token = response.data.token
        let headerAuth = 'Bearer ' + response.data.token

        store.dispatch('saveToken', token)

        axios.defaults.headers['Authorization'] = headerAuth
        originalRequest.headers['Authorization'] = headerAuth

        return axios(originalRequest)
      }).catch((error) => {
        store.dispatch('logout').then(() => {
          router.push({ name: 'login' })
        }).catch(() => {
          router.push({ name: 'login' })
        })
      })*/
    }

    return Promise.reject(error)
  }
)