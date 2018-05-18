<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card>
          <v-card-title primary-title>
            <h3 class="headline mb-0">Cotizaciones</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title>
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
                          v-if="permission('quotations.show')" 
                          icon class="mx-0" 
                          :to="{ name: 'ShowQuotation', params: { id: props.item.id }}"
                        >
                          <v-icon color="grey darken-1">visibility</v-icon>
                        </v-btn>
                      </td>
                      <td>{{ props.item.cite }}</td>
                      <td>{{ props.item.customer }}</td>
                      <td>{{ props.item.total }}</td>
                      <td>{{ props.item.created_at | formatDate('DD/MM/YYYY') }}</td>
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

  export default {
    name: 'list-quotations',
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
          { text: 'Cite', value: 'cite' },
          { text: 'Cliente', value: 'cliente' },
          { text: 'Total/Monto', value: 'total' },
          { text: 'Registrado', value: 'registrado' }
        ],
        items: [],
        totalItems: 0,
        pagination: {
          rowsPerPage: 10
        }
      }
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
            let items = response.data.quotations.data
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
        return `api/quotations${page}${rowsPerPage}${filter}`
      }
    }    
  }
</script>

