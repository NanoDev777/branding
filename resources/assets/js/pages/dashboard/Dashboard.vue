<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
          <v-card flat>
            <v-container fluid>
              <v-layout row child-flex wrap>
                <div>
                  <v-toolbar color="grey lighten-2">
                    <v-toolbar-title>Total Productos</v-toolbar-title>
                    <v-btn icon class="hidden-xs-only">
                      <v-icon>assignment_turned_in</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <span class="headline">{{ products }}</span>
                  </v-toolbar>
                </div>
                <div>
                  <v-toolbar color="yellow lighten-2">
                    <v-toolbar-title>Total Cotizaciones</v-toolbar-title>
                    <v-btn icon class="hidden-xs-only">
                      <v-icon>add_shopping_cart</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <span class="headline">{{ quotations }}</span>
                  </v-toolbar>
                </div>
              </v-layout>
            </v-container>
          </v-card>
          <div v-if="currentUser.profile_id == 1">
            <v-card flat>
              <v-container fluid>
                <v-layout row child-flex wrap>
                  <div>
                    <v-toolbar color="blue lighten-4">
                      <v-toolbar-title>Aprobados</v-toolbar-title>
                      <v-spacer></v-spacer>
                      <span class="headline">{{ approved }}</span>
                    </v-toolbar>
                  </div>
                  <div>
                    <v-toolbar color="orange lighten-4">
                      <v-toolbar-title>Pendientes</v-toolbar-title>
                      <v-spacer></v-spacer>
                      <span class="headline">{{ slopes }}</span>
                    </v-toolbar>
                  </div>
                  <div>
                    <v-toolbar color="red lighten-4">
                      <v-toolbar-title>Anulados</v-toolbar-title>
                      <v-spacer></v-spacer>
                      <span class="headline">{{ canceled }}</span>
                    </v-toolbar>
                  </div>
                </v-layout>
              </v-container>
            </v-card>
            <graphic></graphic>
          </div>
          <v-container fluid v-else>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-jumbotron color="grey lighten-2">
                  <v-container fill-height>
                    <v-layout align-center>
                      <v-flex>
                        <h3 class="display-3">Bienvenido</h3>
                        <span class="subheading">Realize cotizaciones de sus productos de forma rápida y segura, para generar una cotización rapidamente pulse el botón de color azul. para ver otras opciones a la que usted tiene permisos, dirígase al menu en la parte izquierda de su pantalla.</span>
                        <v-divider class="my-3"></v-divider>
                        <div class="title mb-3">Cotización PDF!</div>
                        <v-btn to="quotations/create" large color="primary" class="mx-0">GENERAR</v-btn>
                      </v-flex>
                    </v-layout>
                  </v-container>
                </v-jumbotron>
              </v-flex>
            </v-layout>
          </v-container>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import { mapGetters } from 'vuex'
  import Graphic from './Graphic'

  export default {
    name: 'dashboard',
    data () {
      return {
        success: false,
        products: null,
        quotations: null,
        approved: null,
        slopes: null,
        canceled: null
      }
    },

    computed: {
      ...mapGetters([
        'currentUser'
      ])
    },

    components: {
      'graphic' : Graphic,
    },

    created(){
      this.getTotalProductsQuotaions()
      this.states()
    },

    methods: {
     getTotalProductsQuotaions() {
        axios.get('/api/total-products')
        .then(response => {
          if (response.data.success) {
            this.products = response.data.data.products
            this.quotations = response.data.data.quotations
          }
          this.success = response.data.success
        })
      },

      states() {
        axios.get('/api/quotations-state')
        .then(response => {
          if (response.data.success) {
            this.approved = response.data.data.approved
            this.slopes = response.data.data.slopes
            this.canceled = response.data.data.canceled
          }
          this.success = response.data.success
        })
      }
    }
  }
</script>
