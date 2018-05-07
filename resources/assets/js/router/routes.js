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
import Quotation from '../pages/quotation/Quotation.vue'

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
        name: 'Quotation',
        component: Quotation
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
