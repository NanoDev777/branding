<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card>
          <v-card-title>
            <h3 class="headline mb-0">Lista de productos</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title>
                    <v-btn dark color="grey darken-1" slot="activator" to="products/register">
                      <v-icon dark>note_add</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      @keypress.enter.prevent="getDataFromApi"
                      append-icon="search"
                      label="Buscar"
                      single-line
                      hide-details
                    ></v-text-field>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="items"
                    :pagination.sync="pagination"
                    :total-items="totalItems"
                    :loading="loading"
                    class="elevation-1"
                  > 
                    <v-progress-linear height="5" slot="progress" color="warning" indeterminate></v-progress-linear>
                    <template slot="items" slot-scope="props">
                      <td class="justify-center layout px-0">
                        <v-btn icon class="mx-0" @click="goProduct(props.item.id)">
                          <v-icon color="grey darken-1">visibility</v-icon>
                        </v-btn>
                      </td>
                      <td>{{ props.item.code }}</td>
                      <td>{{ props.item.name }}</td>
                      <td>{{ props.item.category }}</td>
                    </template>
                    <v-alert slot="no-results" :value="true" color="warning" icon="warning">
                      Su búsqueda de "{{ search }}" no arrojó ningún resultado.
                    </v-alert>
                  </v-data-table>
                </v-card>
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

  export default {
    name: 'products',
    data () {
      return {
        search: '',
        loading: false,
        headers: [
          {
            text: '',
            align: 'left',
            sortable: false,
          },
          { text: 'Código', value: 'code' },
          { text: 'Nombre', value: 'name' },
          { text: 'Categoría', value: 'category' }
        ],
        items: [],
        totalItems: 0,
        pagination: {},
      }
    },

    watch: {
      pagination: {
        handler () {
          this.getDataFromApi()
          .then(data => {
            this.items = data.items
          })
        },
        deep: true
      }
    },

    mounted () {
      this.getDataFromApi()
      .then(data => {
        this.desserts = data.items
      })
    },

    methods: {
      goProduct (item) {
        this.$router.push('/products/profile/' + item)
      },
      getDataFromApi() {
        this.loading = true
        return new Promise((resolve, reject) => {
          const { sortBy, descending, page, rowsPerPage } = this.pagination
          axios.get(this.buildURL())
          .then((response) => {
            this.totalItems = response.data.params.total
            let items = response.data.products.data
            resolve({
              items
            })
            this.loading = false
          })
        })
      },
      buildURL() {
        let page = `?page=${this.pagination.page}`
        let rowsPerPage = `&rowsPerPage=${this.pagination.rowsPerPage}`
        let filter = this.search === '' ? '' : `&filter=${this.search}`
        return `api/products${page}${rowsPerPage}${filter}`
      }
    }
  }
</script>
