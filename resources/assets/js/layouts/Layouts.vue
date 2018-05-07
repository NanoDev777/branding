<template>
  <v-app>
    <v-navigation-drawer
      persistent
      :mini-variant="miniVariant"
      :clipped="clipped"
      v-model="drawer"
      :width="width"
      enable-resize-watcher
      fixed
      app
    >
      <v-toolbar flat>
        <v-list>
          <v-list-tile>
              <img src="/img/branding.png" width="180px" v-if="!miniVariant">
              <img src="/img/branding2.png" width="45px" v-else="miniVariant">
          </v-list-tile>
        </v-list>
      </v-toolbar>
      <v-divider></v-divider>
      <v-list dense class="pt-0">
        <v-list-tile v-for="item in items" :key="item.title" router :to="item.url">
          <v-list-tile-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>{{ item.title }}</v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-navigation-drawer>
    <app-toolbar 
      v-on:toggleDrawer="drawer = !drawer" :drawer="drawer" 
      v-on:toggleMiniVariant="miniVariant = !miniVariant" :miniVariant="miniVariant"
    >
    </app-toolbar>
    <v-content>
      <transition name="fade">
        <router-view></router-view>
      </transition>
    </v-content>
    <app-footer/>
  </v-app>
</template>

<script>
  import AppFooter from '../components/AppFooter.vue'
  import AppToolbar from '../components/AppToolbar.vue'
  import AppBreadcrumbs from '../components/AppBreadcrumbs.vue'

  export default {
    name: 'layouts',
    components: {
      'app-footer': AppFooter,
      'app-toolbar': AppToolbar,
      'app-breadcrumbs': AppBreadcrumbs
    },
    data () {
      return {
        clipped: false,
        drawer: true,
        miniVariant: false,
        items: [
          { icon: 'home', title: 'Inicio', url: '/dashboard' },
          { icon: 'local_grocery_store', title: 'Productos', url: '/products' },
          { icon: 'local_offer', title: 'Categorías', url: '/categories' },
          { icon: 'monetization_on', title: 'Precios', url: '/prices' },
          { icon: 'find_in_page', title: 'Cotización', url: '/quotations' },
        ],
        width: 220
      }
    },
    computed: {
      name () {
        return this.$route.name
      },
      list () {
        return this.$route.matched
      }
    }
  }
</script>

<style>
.fade-enter-active, .fade-leave-active {
  transition-property: opacity;
  transition-duration: .25s;
}

.fade-enter-active {
  transition-delay: .25s;
}

.fade-enter, .fade-leave-active {
  opacity: 0
}
</style>