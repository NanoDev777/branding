<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card>
          <v-card-title primary-title>
            <h3 class="headline mb-0">Cotizaciones</h3>
          </v-card-title>
          <v-container fluid>
            <v-dialog v-model="dialog" persistent max-width="290">
              <v-card>
                <v-card-title class="headline">Anular Cotización</v-card-title>
                <v-card-text>Realmente desea anular este registro y cambiar su estado actual?</v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" flat @click.native="dialog = false" :disabled="loader">Cancelar</v-btn>
                  <v-btn color="blue darken-1" flat @click.native="deleted" :loading="loader">Aceptar</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
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
                      <td>
                        <v-chip v-if="props.item.state === 0" color="orange lighten-3" label small class="ml-0">
                          Pendiente
                        </v-chip>
                        <v-chip v-else-if="props.item.state === 1" color="blue lighten-3" label small class="ml-0">
                          Aprobado
                        </v-chip>
                        <v-chip v-else color="red lighten-3" label small class="ml-0">
                          Anulado
                        </v-chip>
                      </td>
                      <td>{{ props.item.created_at | formatDate('DD/MM/YYYY') }}</td>
                      <td>
                        <v-btn 
                          icon class="mx-0" 
                          @click.native="showModal(props.item.id)"
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

  export default {
    name: 'list-quotations',
    data () {
      return {
        search: '',
        loading: false,
        dialog: false,
        loader: false,
        headers: [
          {
            text: '',
            align: 'left',
            sortable: false,
          },
          { text: 'Cite', value: 'cite' },
          { text: 'Cliente', value: 'cliente' },
          { text: 'Estado', value: 'estado' },
          { text: 'Generado', value: 'generado' },
          { text: 'Acción', value: 'accion' }
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
      showModal(id) {
        this.dialog = true
        this.id = id
      },
      
      deleted() {
        this.loader = true
        axios.put(`/api/cancel-quotation/${this.id}`)
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

