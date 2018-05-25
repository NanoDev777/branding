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
                    <v-toolbar-title>Cotización - Cite # {{ cite }}</v-toolbar-title>
                  </v-toolbar>
                  <v-card-text>
                    <v-list two-line subheader>
                      <v-subheader>Detalles Generales</v-subheader>
                      <v-list-tile avatar>
                        <v-list-tile-content>
                          <v-list-tile-title>Fecha de Emisión</v-list-tile-title>
                          <v-list-tile-sub-title>{{ registro | formatDate('DD/MM/YYYY') }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      <v-list-tile avatar>
                        <v-list-tile-content>
                          <v-list-tile-title>Cliente</v-list-tile-title>
                          <v-list-tile-sub-title>{{ customer }}</v-list-tile-sub-title>
                        </v-list-tile-content>
                      </v-list-tile>
                      <v-list-tile avatar>
                        <v-list-tile-content>
                          <v-list-tile-title>Monto / Precio Total</v-list-tile-title>
                          <v-list-tile-sub-title>{{ total }} Bs.</v-list-tile-sub-title>
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
                    <template slot="expand" slot-scope="props">
                      <v-card flat>
                        <v-card-text>Peek-a-boo!</v-card-text>
                      </v-card>
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
  export default {
    name: 'show-quotation',
    props: ["id"],
    data () {
      return {
        success: false,
        cite: null,
        customer: '',
        total: null,
        registro: '',
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
            this.total = response.data.data.total
            this.registro = response.data.data.created_at
            this.products = response.data.data.products
            this.success = response.data.success
          }
        })
      }
    }    
  }
</script>

