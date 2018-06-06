<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-toolbar color="teal" dark>
                    <v-toolbar-side-icon><v-icon>description</v-icon></v-toolbar-side-icon>
                    <v-toolbar-title>Cotización - Cite # {{ cite }} </v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                    <v-list two-line subheader>
                      <v-subheader>Detalles Generales</v-subheader>
                      <v-layout>
                        <v-flex xs12 sm12 md12 lg12>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md3 lg3>
                              <v-list-tile v-if="state != 2">
                                <v-list-tile-content>
                                  <v-list-tile-title>Estado</v-list-tile-title>
                                  <v-list-tile-sub-title>
                                    <v-checkbox
                                      :label="`${state == true ? 'Aprobado' : 'Pendiente'}`" 
                                      v-model="state"
                                      color="teal"
                                    ></v-checkbox>
                                  </v-list-tile-sub-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                  <v-btn title="Cambiar Estado" icon ripple :loading="loading" @click="submit">
                                    <v-icon color="grey lighten-1">info</v-icon>
                                  </v-btn>
                                </v-list-tile-action>
                              </v-list-tile>
                              <v-list-tile v-else>
                                <v-list-tile-content>
                                  <v-list-tile-title>Estado</v-list-tile-title>
                                  <v-list-tile-sub-title>
                                    <v-chip color="red lighten-3" label small class="ml-0">
                                      Anulado
                                    </v-chip>
                                    Fecha: {{ updated | formatDate('DD/MM/YYYY') }}
                                  </v-list-tile-sub-title>
                                </v-list-tile-content>
                              </v-list-tile>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                      <v-list-tile>
                        <v-list-tile-content>
                          <v-list-tile-title>Fecha de Emisión</v-list-tile-title>
                          <v-list-tile-sub-title>{{ created | formatDate('DD/MM/YYYY') }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      <v-list-tile>
                        <v-list-tile-content>
                          <v-list-tile-title>Cliente</v-list-tile-title>
                          <v-list-tile-sub-title>{{ customer }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      <v-layout>
                        <v-flex xs12 sm12 md12 lg12>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md3 lg3>
                              <v-list-tile>
                                <v-list-tile-content>
                                  <v-list-tile-title>Persona de contacto</v-list-tile-title>
                                  <v-list-tile-sub-title>{{ contact }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                              </v-list-tile>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg3>
                              <v-list-tile>
                                <v-list-tile-content>
                                  <v-list-tile-title>Teléfono</v-list-tile-title>
                                  <v-list-tile-sub-title>{{ phone }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                              </v-list-tile>
                            </v-flex>
                            <v-flex xs12 sm12 md3 lg3>
                              <v-list-tile>
                                <v-list-tile-content>
                                  <v-list-tile-title>Dirección</v-list-tile-title>
                                  <v-list-tile-sub-title>{{ address }}</v-list-tile-sub-title>
                                </v-list-tile-content>
                              </v-list-tile>
                            </v-flex>
                          </v-layout>
                        </v-flex>
                      </v-layout>
                      <v-list-tile>
                        <v-list-tile-content>
                          <v-list-tile-title>Plazo de Entrega</v-list-tile-title>
                          <v-list-tile-sub-title>{{ term }} Días</v-list-tile-sub-title>
                          <small>(Establecido al momento de realizar la cotización.)</small>
                        </v-list-tile-content>
                      </v-list-tile>
                    </v-list>
                    <v-btn
                      color="teal"
                      dark
                      small
                      absolute
                      bottom
                      right
                      fab
                      :loading="pdf"
                      @click="detailPDF"
                    >
                      <v-icon>get_app</v-icon>
                    </v-btn>
                  </v-card-text>
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title>
                    <b>Productos Requeridos</b>
                  </v-card-title>
                  <v-data-table
                    :headers="headers"
                    :items="products"
                    hide-actions
                    item-key="name"
                  >
                    <template slot="items" slot-scope="props">
                      <tr @click="props.expanded = !props.expanded">
                        <td>{{ props.item.code }}</td>
                        <td class="text-xs-left">{{ props.item.name }}</td>
                        <td class="text-xs-left">{{ props.item.pivot.quantity }}</td>
                        <td class="text-xs-left">{{ props.item.pivot.total | currency}}</td>
                        <td class="text-xs-left">{{ props.item.pivot.quantity * props.item.pivot.total | currency }}</td>
                      </tr>
                    </template>
                    <!--<template slot="expand" slot-scope="props">
                      <v-card flat>
                        <v-card-text>Peek-a-boo!</v-card-text>
                      </v-card>
                    </template>-->
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
  export default {
    name: 'show-quotation',
    data () {
      return {
        success: false,
        loading: false,
        pdf: false,
        id: this.$route.params.id,
        cite: null,
        customer: '',
        contact: '',
        phone: '',
        address: '',
        term: '',
        state: null,
        created: '',
        updated: '',
        products: [],
        headers: [
          {
            text: 'Código',
            align: 'left',
            sortable: false,
            value: 'name'
          },
          { text: 'Nombre', value: 'nombre' },
          { text: 'Cantidad', value: 'cantidad' },
          { text: 'Precio Unitario', value: 'unitario' },
          { text: 'Total', value: 'total' }
        ]
      }
    },

    created() {
      this.showQuotation()
      console.log(this.customer)
    },

    methods: {
      showQuotation(){
        axios.get(`/api/quotation/${this.id}`)
        .then(response => {
          if (response.data.success) {
            this.cite = response.data.data.cite
            this.customer = response.data.data.customer
            this.contact = response.data.data.contact
            this.phone = response.data.data.phone
            this.address = response.data.data.address
            this.term = response.data.data.term
            this.state = response.data.data.state
            this.created = response.data.data.created_at
            this.updated = response.data.data.updated_at
            this.products = response.data.data.products
            this.success = true
          }
        })
      },

      submit(){
        this.loading = true
        axios.put(`/api/quotation/${this.id}`, {state: this.state})
        .then(response => {
          if(response.data.success) {
            this.$snotify.simple(response.data.message, 'Felicidades')
          }
          this.loading = false
        })
        .catch(error =>{
          this.loading = false
        })
      },

      detailPDF () {
        this.pdf = true
        axios.get(`/api/detail-reporte/${this.id}`)
        .then((response) => {
          if (response.status === 200) {
            let url = `/${response.data.data}`
            window.open(
              url,
              '_blank' 
            )
            this.pdf = false
          }
        })
        .catch((error) => {
          this.pdf = false
        })
      } 
    }   
  }
</script>

