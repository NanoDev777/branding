<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
       <v-progress-circular indeterminate :size="70" :width="7" color="grey darken-1" v-if="products.length === 0 || categories.length === 0"></v-progress-circular>
        <v-card v-show="products.length != 0 && categories.length != 0">
          <v-card-title>
            <v-layout row wrap>
              <h3 class="headline mb-0">Lista de productos</h3>
              <v-spacer></v-spacer>
              <v-flex d-flex xs12 sm6 md4>
                <v-select
                  :items="categories"
                  v-model="filter"
                  label="Filtrar por categoria"
                  auto
                  change="searchProducts"
                  item-text="name"
                  item-value="id"
                  single-line
                  prepend-icon="find_replace"
                  hide-details
                ></v-select>
                <h1>{{filter}}</h1>
              </v-flex>
              <v-flex d-flex xs12 sm6 md4>
                <v-text-field
                  append-icon="search"
                  label="Buscar"
                  single-line
                  hide-details
                  v-model="search"
                ></v-text-field>
              </v-flex>
            </v-layout>
          </v-card-title>
          <v-layout row wrap>
            <v-flex>
              <v-tooltip right>
                <v-btn dark color="grey darken-1" slot="activator" to="products/register">
                  <v-icon dark>add</v-icon>
                </v-btn>
                <span>Nuevo producto</span>
              </v-tooltip>
            </v-flex>
          </v-layout>
          <v-data-table
            :headers="headers"
            :items="products"
            :search="search"
          >
            <template slot="items" slot-scope="props">
              <td class="justify-center layout px-0">
                <v-btn icon class="mx-0" @click="goProduct(props.item.id)">
                  <v-icon color="blue">visibility</v-icon>
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
</template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    name: 'products',
    data () {
      return {
        search: '',
        items: [],
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
        filter: null,
        category: []
      }
    },

    computed: {
      ...mapGetters([
        'products',
        'categories'
      ])
    },

    created() {
      if (this.products.length === 0) 
        this.$store.dispatch("getProducts")
      if (this.categories.length === 0) 
        this.$store.dispatch("getCategories") 
    },

    methods: {
      goProduct (item) {
        this.$router.push('/products/profile/' + item)
      },
      searchProducts (category) {
        const url = '/api/filter-products/'+ category
        axios.get(url)
        .then( response => {
          console.log(response)
        })
        .catch( error => {
          //this.message = error.response.data.error
        })
      }
    }
  }
</script>
