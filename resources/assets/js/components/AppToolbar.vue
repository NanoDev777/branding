<template>
  <v-toolbar
    color="yellow accent-3" dense fixed clipped-left app
    app
    :clipped-left="clipped"
  >
    <v-toolbar-side-icon @click.stop="toggleDrawer"></v-toolbar-side-icon>
    <v-btn icon @click.stop="toggleMiniVariant">
      <v-icon v-html="miniVariant ? 'chevron_right' : 'chevron_left'"></v-icon>
    </v-btn>
    <v-spacer></v-spacer>
    <v-toolbar-title v-if="currentUser" v-text="currentUser.name"></v-toolbar-title>
    <v-menu offset-y>
      <v-btn icon slot="activator"><v-icon>menu</v-icon></v-btn>
      <v-list>
        <v-list-tile @click="">
          <v-list-tile-action>
            <v-icon>settings</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>
              Configuración
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
        <v-list-tile @click="logout">
          <v-list-tile-action>
            <v-icon>exit_to_app</v-icon>
          </v-list-tile-action>
          <v-list-tile-content>
            <v-list-tile-title>
              Cerrar sesión
            </v-list-tile-title>
          </v-list-tile-content>
        </v-list-tile>
      </v-list>
    </v-menu>
  </v-toolbar>
</template>

<script>
import { mapGetters } from 'vuex'

  export default {
    name: 'apptoolbar',
    props: {
      drawer: {
        type: Boolean,
        required: true
      },
      miniVariant: {
        type: Boolean,
        required: true
      }
    },
    data: () => ({
      clipped: false
    }),
    computed: {
      ...mapGetters([
        'currentUser',
        'authenticated'
      ])
    },
    methods: {
      async logout() {
        await this.$store.dispatch('logout', this.currentUser.id)
        this.$router.push({ name: 'login' })
      },
      toggleDrawer () {
        this.$emit('toggleDrawer')
      },
      toggleMiniVariant () {
        this.$emit('toggleMiniVariant')
      }
    }
  }
</script>