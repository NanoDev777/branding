import Login from '../pages/auth/Login.vue'
import Layouts from '../layouts/Layouts.vue'
import Dashboard from '../pages/dashboard/Dashboard.vue'

//products
import ListProducts from '../pages/products/ListProducts.vue'
import ShowProduct from '../pages/products/ShowProduct.vue'
import CreateProduct from '../pages/products/CreateProduct.vue'
import EditProduct from '../pages/products/EditProduct.vue'

//categories
import ListCategories from '../pages/category/ListCategories.vue'
import CreateCategory from '../pages/category/CreateCategory.vue'
import EditCategory from '../pages/category/EditCategory.vue'

//prices
import ListPrices from '../pages/price/ListPrices.vue'
import CreatePrice from '../pages/price/CreatePrice.vue'
import EditPrice from '../pages/price/EditPrice.vue'

//quotation
import Quotations from '../pages/quotation/ListQuotations.vue'
import CreateQuotation from '../pages/quotation/CreateQuotation.vue'
import ShowQuotation from '../pages/quotation/ShowQuotation.vue'

//users
import ListUsers from '../pages/users/ListUsers.vue'
import CreateUser from '../pages/users/CreateUser.vue'
import EditUser from '../pages/users/EditUser.vue'

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
            component: CreateProduct
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
            component: EditProduct,
            props: true
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
            component: CreateCategory,
            props: true
          },
          {
            path: ':id/edit',
            name: 'EditCategory',
            component: EditCategory,
            props: true
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
            component: CreatePrice,
            props: true
          },
          {
            path: ':id/edit',
            name: 'EditPrice',
            component: EditPrice,
            props: true
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
            component: CreateUser
          },
          {
            path: ':id/edit',
            name: 'EditUser',
            component: EditUser,
            props: true
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
