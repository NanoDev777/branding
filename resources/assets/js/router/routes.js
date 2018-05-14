import Login from '../pages/auth/Login.vue'
import Layouts from '../layouts/Layouts.vue'
import Dashboard from '../pages/dashboard/Dashboard.vue'

//products
import Products from '../pages/products/Products.vue'
import Product from '../pages/products/Product.vue'
import ProductRegister from '../pages/products/CreateProduct.vue'
import EditProduct from '../pages/products/EditProduct.vue'

//categories
import Category from '../pages/category/Category.vue'

//prices
import Price from '../pages/price/Price.vue'

//quotation
import Quotations from '../pages/quotation/ListQuotations.vue'
import CreateQuotation from '../pages/quotation/CreateQuotation.vue'
import ShowQuotation from '../pages/quotation/ShowQuotation.vue'

//users
import Users from '../pages/users/ListUsers.vue'
import CreateUser from '../pages/users/CreateUser.vue'
import EditUser from '../pages/users/EditUser.vue'

//roles
import Roles from '../pages/roles/ListRoles.vue'
import CreateRol from '../pages/roles/CreateRol.vue'
import EditRol from '../pages/roles/EditRol.vue'

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
        redirect: '/products/',
        name: 'Products',
        component: {
          render (c) { return c('router-view') }
        },
        children: [
          {
            path: '',
            name: 'List',
            component: Products
          },
          {
            path: 'register',
            name: 'ProductRegister',
            component: ProductRegister
          },
          {
            path: 'profile/:id',
            name: 'Product',
            component: Product,
            props: true
          },
          {
            path: 'edit/:id',
            name: 'EditProduct',
            component: EditProduct,
            props: true
          }
        ]
      },
      {
        path: '/categories',
        name: 'Category',
        component: Category
      },
      {
        path: '/prices',
        name: 'Price',
        component: Price
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
            path: ':id/show',
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
            component: Users
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
        path: '/roles',
        name: 'Roles',
        redirect: '/roles',
        component: {
          render (c) { return c('router-view') }
        },
        children: [
          {
            path: '',
            name: 'ListRoles',
            component: Roles
          },
          {
            path: 'create',
            name: 'CreateRol',
            component: CreateRol
          },
          {
            path: ':id/edit',
            name: 'EditRol',
            component: EditRol,
            props: true
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
