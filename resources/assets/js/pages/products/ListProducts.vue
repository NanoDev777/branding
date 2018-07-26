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
              <v-flex d-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title>
                    <v-container fluid grid-list-md>
                      <v-layout row wrap>
                        <v-flex d-flex xs12 sm12 md3 lg3>
                          <v-layout row wrap>
                            <v-flex d-flex>
                              <v-btn
                                v-if="permission('products.create')"
                                dark color="grey darken-1" 
                                slot="activator" 
                                class="mb-2" 
                                to="products/create"
                              >
                                <v-icon dark>note_add</v-icon>
                              </v-btn>
                            </v-flex>
                            <v-flex d-flex>
                              <v-layout row wrap>
                                <v-flex xs12 sm12 class="py-2">
                                  <v-btn-toggle v-model="toggle_one" mandatory>
                                    <v-btn flat>
                                      <v-icon>format_align_justify</v-icon>
                                    </v-btn>
                                    <v-btn flat>
                                      <v-icon>format_align_center</v-icon>
                                    </v-btn>
                                  </v-btn-toggle>
                                </v-flex>
                              </v-layout>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                        <v-spacer></v-spacer>
                        <v-flex d-flex xs12 sm12 md4 lg4>
                          <v-select
                            :items="categories"
                            v-model="category"
                            @change="filterCategory"
                            label="Categorías"
                            item-text="name"
                            item-value="name"
                            autocomplete
                          ></v-select>
                        </v-flex>
                        <v-flex d-flex xs12 sm12 md4 lg4>
                          <v-text-field
                            v-model="search"
                            @keypress.enter.prevent="filterData"
                            append-icon="search"
                            label="Buscar"
                            single-line
                          ></v-text-field>
                        </v-flex>
                      </v-layout>
                    </v-container>
                  </v-card-title>
                  <v-data-table
                    v-if="toggle_one == 0"
                    :headers="headers"
                    :items="items"
                    :pagination.sync="pagination"
                    :total-items="totalItems"
                    :rows-per-page-items="rowsPerPageItems"
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
                  <v-data-iterator
                    v-else
                    :items="items"
                    :rows-per-page-items="rowsPerPageItems"
                    :pagination.sync="pagination"
                    :total-items="totalItems"
                    content-tag="v-layout"
                    row
                    wrap
                  >
                    <v-flex
                      slot="item"
                      slot-scope="props"
                      xs12
                      sm6
                      md4
                      lg3
                    >
                      <v-card>
                        <v-card-title><h5>{{ props.item.name }}</h5></v-card-title>
                        <v-divider></v-divider>
                        <div id="container">
                          <img class="image" :src="'/img/products/'+props.item.image" alt="Image did not load..."/>
                        </div>
                        <v-list>
                          <v-list-tile>
                            <v-list-tile-content>
                              <v-list-tile-title><strong>{{ props.item.code }}</strong></v-list-tile-title>
                            </v-list-tile-content>
                          </v-list-tile>
                          <v-list-tile>
                            <v-list-tile-content>
                              <v-list-tile-title>Categoría</v-list-tile-title>
                              <v-list-tile-sub-title>{{ props.item.category }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                          <v-list-tile>
                            <v-list-tile-content>
                              <v-list-tile-title>Registrado</v-list-tile-title>
                              <v-list-tile-sub-title>{{ props.item.created_at | formatDate('DD/MM/YYYY') }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                          <v-list-tile>
                            <v-list-tile-content>
                              <v-list-tile-title>Precio (c/mínima)</v-list-tile-title>
                              <v-list-tile-sub-title>{{ props.item.category }}</v-list-tile-sub-title>
                            </v-list-tile-content>
                          </v-list-tile>
                        </v-list>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn 
                            v-if="permission('products.show')" 
                            icon class="mx-0" 
                            :to="{ name: 'ShowProduct', params: { id: props.item.id }}"
                          >
                            <v-icon color="grey darken-1">visibility</v-icon>
                          </v-btn>
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
                        </v-card-actions>
                      </v-card>
                    </v-flex>
                    <template slot="no-data">
                      <center>Sin Resultados</center>
                    </template>
                  </v-data-iterator>
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
  import CategoryService from '../../class/category/CategoryService'

  export default {
    name: 'list-products',
    data () {
      return {
        categories: [],
        category: '',
        toggle_one: 0,
        search: '',
        dialog: false,
        loader: false,
        loading: false,
        items: [],
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
        totalItems: 0,
        rowsPerPageItems: [4, 8, 12],
        pagination: {
          rowsPerPage: 12
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

    created() {
      let categories = new CategoryService(axios.get('/api/list-categories'))
      categories.list()
      .then(categories => {
        this.categories = categories.list
      })
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

      filterCategory(a) {
        this.search = ''
        this.category = a
        this.getDataFromApi().then(data =>{
          this.items = data.items
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
        let filter = this.search === '' ? `&filter=${this.category}` : `&filter=${this.search}`
        return `api/products${page}${rowsPerPage}${filter}`
      }
    }
  }
</script>

<style scoped>
  #container {
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 150px;
  }

  .image {
    flex-grow: 1;
    height: 100%;
  }
</style>