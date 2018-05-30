import Login from '../pages/auth/Login.vue'
import Layouts from '../layouts/Layouts.vue'
import Dashboard from '../pages/dashboard/Dashboard.vue'

//products
import ListProducts from '../pages/products/ListProducts.vue'
import ShowProduct from '../pages/products/ShowProduct.vue'
import FormProduct from '../pages/products/FormProduct.vue'
//import EditProduct from '../pages/products/EditProduct.vue'

//categories
import ListCategories from '../pages/category/ListCategories.vue'
import FormCategory from '../pages/category/FormCategory.vue'
//import EditCategory from '../pages/category/EditCategory.vue'

//prices
import ListPrices from '../pages/price/ListPrices.vue'
import FormPrice from '../pages/price/FormPrice.vue'
//import EditPrice from '../pages/price/EditPrice.vue'

//costs
import Costs from '../pages/costs/Costs.vue'

//quotation
import Quotations from '../pages/quotation/ListQuotations.vue'
import CreateQuotation from '../pages/quotation/CreateQuotation.vue'
import ShowQuotation from '../pages/quotation/ShowQuotation.vue'

//users
import ListUsers from '../pages/users/ListUsers.vue'
import FormUser from '../pages/users/FormUser.vue'
import Password from '../pages/users/Password.vue'
//import EditUser from '../pages/users/EditUser.vue'

//profiles
import ListProfiles from '../pages/profile/ListProfiles.vue'
import FormProfile from '../pages/profile/FormProfile.vue'

export default [
  {
    path: '/',
    name: 'Home',
    component: Layouts,
    redirect: '/dashboard',
    meta: { requiresAuth: true },
    children: [
      {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard
      },
      {
        path: '/products',
        redirect: '/products',
        name: 'Products',
        component: {
          render (c) { return c('router-view') }
        },
        children: [
          {
            path: '',
            name: 'ListProducts',
            component: ListProducts
          },
          {
            path: 'create',
            name: 'CreateProduct',
            component: FormProduct
          },
          {
            path: ':id',
            name: 'ShowProduct',
            component: ShowProduct,
            props: true
          },
          {
            path: ':id/edit',
            name: 'EditProduct',
            component: FormProduct
          }
        ]
      },
      {
        path: '/categories',
        name: 'Categories',
        redirect: '/categories',
        component: {
          render (c) { return c('router-view') }
        },
        children: [
          {
            path: '',
            name: 'ListCategories',
            component: ListCategories
          },
          {
            path: 'create',
            name: 'CreateCategory',
            component: FormCategory
          },
          {
            path: ':id/edit',
            name: 'EditCategory',
            component: FormCategory
          }
        ]
      },
      {
        path: '/prices',
        name: 'Prices',
        redirect: '/prices',
        component: {
          render (c) { return c('router-view') }
        },
        children: [
          {
            path: '',
            name: 'ListPrices',
            component: ListPrices
          },
          {
            path: 'create',
            name: 'CreatePrice',
            component: FormPrice
          },
          {
            path: ':id/edit',
            name: 'EditPrice',
            component: FormPrice
          }
        ]
      },
      {
        path: '/quotations',
        name: 'Quotations',
        redirect: '/quotations',
        component: {
          render (c) { return c('router-view') }
        },
        children: [
          {
            path: '',
            name: 'ListQuotations',
            component: Quotations
          },
          {
            path: 'create',
            name: 'CreateQuotation',
            component: CreateQuotation
          },
          {
            path: ':id',
            name: 'ShowQuotation',
            component: ShowQuotation,
            props: true
          }
        ]
      },
      {
        path: '/users',
        name: 'Users',
        redirect: '/users',
        component: {
          render (c) { return c('router-view') }
        },
        children: [
          {
            path: '',
            name: 'ListUsers',
            component: ListUsers
          },
          {
            path: 'create',
            name: 'CreateUser',
            component: FormUser
          },
          {
            path: ':id/edit',
            name: 'EditUser',
            component: FormUser
          }
          ,
          {
            path: ':id/password',
            name: 'Password',
            component: Password
          }
        ]
      },
      {
        path: '/profiles',
        name: 'Profiles',
        redirect: '/profiles',
        component: {
          render (c) { return c('router-view') }
        },
        children: [
          {
            path: '',
            name: 'ListProfiles',
            component: ListProfiles
          },
          {
            path: 'create',
            name: 'CreateProfile',
            component: FormProfile
          },
          {
            path: ':id/edit',
            name: 'EditProfile',
            component: FormProfile,
          }
        ]
      },
      {
        path: '/costs', 
        name: 'Costs', 
        component: Costs
      }
    ]  
  },
  {
    path: '/login', 
    name: 'login', 
    component: Login, 
    meta: { 
      redirectIfLogged: true 
    } 
  }
]
