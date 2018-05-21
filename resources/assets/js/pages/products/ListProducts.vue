<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card>
          <modal-delete :loader="loader" :dialog="dialog" @hide="hide" @deleted="deleted"></modal-delete>
          <v-card-title>
            <h3 class="headline mb-0">Lista de productos</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title>
                    <v-btn
                      v-if="permission('products.create')"
                      dark color="grey darken-1" 
                      slot="activator" 
                      class="mb-2" 
                      to="products/create"
                    >
                      <v-icon dark>note_add</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field
                      v-model="search"
                      @keypress.enter.prevent="filterData"
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
                        <v-btn 
                          v-if="permission('products.show')" 
                          icon class="mx-0" 
                          :to="{ name: 'ShowProduct', params: { id: props.item.id }}"
                        >
                          <v-icon color="grey darken-1">visibility</v-icon>
                        </v-btn>
                      </td>
                      <td class="text-xs-left">{{ props.item.code }}</td>
                      <td class="text-xs-left">{{ props.item.name }}</td>
                      <td class="text-xs-left">{{ props.item.category }}</td>
                      <td class="text-xs-left">{{ props.item.created_at | formatDate('DD/MM/YYYY') }}</td>
                      <td>
                        <v-btn 
                          v-if="permission('products.update')" 
                          icon class="mx-0" 
                          :to="{ name: 'EditProduct', params: { id: props.item.id }}"
                        >
                          <v-icon color="teal">edit</v-icon>
                        </v-btn>
                        <v-btn 
                          v-if="permission('products.delete')" 
                          icon class="mx-0" 
                          @click="showModal(props.item.id)"
                        >
                          <v-icon color="pink">delete</v-icon>
                        </v-btn>
                      </td>
                    </template>
                    <template slot="no-data">
                      <center>Sin Resultados</center>
                    </template>
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
  import permission from '../../mixins/permission'
  import ModalDelete from '../../components/ModalDelete.vue'

  export default {
    name: 'list-products',
    data () {
      return {
        search: '',
        dialog: false,
        loader: false,
        loading: false,
        headers: [
          {
            text: '',
            align: 'left',
            sortable: false,
          },
          { text: 'Código', value: 'codigo' },
          { text: 'Nombre', value: 'nombre' },
          { text: 'Categoría', value: 'categoria' },
          { text: 'Registrado', value: 'registrado' },
          { text: 'Acciones', value: 'acciones' }
        ],
        items: [],
        totalItems: 0,
        pagination: {
          rowsPerPage: 10
        }
      }
    },

    components: {
      'modal-delete' : ModalDelete
    },

    mixins: [permission],

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

    methods: {
      showModal(id) {
        this.dialog = true
        this.id = id
      },

      hide() {
        this.dialog = false
      },

      deleted() {
        this.loader = true
        axios.delete(`/api/product/${this.id}`)
        .then((response) => {
          this.loader = false
          this.dialog = false
          this.$snotify.simple(response.data.message, 'Felicidades')
          this.getDataFromApi().then(data =>{
            this.items = data.items
          })
        })
        .catch((error) => {
          this.loader = false
          this.dialog = false
        })
      },

      filterData() {
        this.getDataFromApi().then(data =>{
          this.items = data.items
        })
      },

      getDataFromApi() {
        this.loading = true
        return new Promise((resolve, reject) => {
          const { sortBy, descending, page } = this.pagination
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
