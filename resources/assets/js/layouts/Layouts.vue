<template>
  <div>
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
      <v-list dense>
        <template v-for="item in items">
          <v-layout
            row
            v-if="item.heading"
            align-center
            :key="item.heading"
          >
          </v-layout>
          <v-list-group
            v-else-if="item.children"
            v-model="item.model"
            :key="item.text"
            :prepend-icon="item.model ? item.icon : item['icon-alt']"
            append-icon=""
          >
            <v-list-tile slot="activator">
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ item.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
            <v-list-tile
              v-for="(child, i) in item.children"
              :key="i"
              router :to="child.url"
              v-show="permission(child.permission) || child.permission ==''"
            >
              <v-list-tile-action v-if="child.icon">
                <v-icon>{{ child.icon }}</v-icon>
              </v-list-tile-action>
              <v-list-tile-content>
                <v-list-tile-title>
                  {{ child.text }}
                </v-list-tile-title>
              </v-list-tile-content>
            </v-list-tile>
          </v-list-group>
          <v-list-tile v-else :key="item.text" router :to="item.url" v-show="permission(item.permission) || item.permission ==''">
            <v-list-tile-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-tile-action>
            <v-list-tile-content>
              <v-list-tile-title>
                {{ item.text }}
              </v-list-tile-title>
            </v-list-tile-content>
          </v-list-tile>
        </template>
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
  </div>
</template>

<script>
  import AppFooter from '../components/AppFooter.vue'
  import AppToolbar from '../components/AppToolbar.vue'
  import permission from '../mixins/permission'

  export default {
    name: 'layouts',
    components: {
      'app-footer': AppFooter,
      'app-toolbar': AppToolbar
    },
    data () {
      return {
        clipped: false,
        drawer: true,
        miniVariant: false,
        items: [
        { icon: 'home', text: 'Inicio', url: '/dashboard', permission: '' },
        { icon: 'local_grocery_store', text: 'Productos', url: '/products', permission: 'products.index' },
        { icon: 'local_offer', text: 'Categorías', url: '/categories', permission: 'categories.index' },
        { icon: 'monetization_on', text: 'Precios', url: '/prices', permission: 'prices.index' },
        {
          icon: 'find_in_page',
          'icon-alt': 'find_in_page',
          text: 'Cotización',
          model: false,
          children: [
            { icon: 'assignment', text: 'Ver lista', url: '/quotations', permission: 'quotations.index' },
            { icon: 'note_add', text: 'Generar nueva', url: '/quotations/create', permission: 'quotations.create' }
          ]
        },
        {
          icon: 'settings',
          'icon-alt': 'settings',
          text: 'Configuración',
          model: false,
          children: [
            { icon: 'security', text: 'Perfil y permisos', url: '/profiles', permission: 'profiles.index' },
            { icon: 'group', text: 'Usuarios', url: '/users', permission: 'users.index' }
          ]
        }
      ],
        width: 220
      }
    },

    mixins: [permission]
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