<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout row wrap>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
          <v-card-title primary-title>
            <h3 class="headline mb-0">Costos</h3>
            <v-spacer></v-spacer>
            <div>
              <b>Registrado: </b>{{ cost.created_at | formatDate('DD/MM/YYYY') }}<br>
              <b>Actualizado: </b> {{ cost.updated_at | formatDate('DD/MM/YYYY') }}
             </div>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-text>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md12 lg12>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field
                              label="T/C BS"
                              v-model="cost.chilean"
                              data-vv-name="chilean"
                              v-validate="'required|decimal:2'"
                              :error-messages="errors.collect('chilean')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field
                              label="T/C SUS"
                              v-model="cost.dollar"
                              data-vv-name="dollar"
                              v-validate="'required|decimal:2'"
                              :error-messages="errors.collect('dollar')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field
                              label="Dólar Compra"
                              v-model="cost.purchase"
                              data-vv-name="purchase"
                              v-validate="'required|decimal:2'"
                              :error-messages="errors.collect('purchase')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field
                              label="Dólar venta"
                              v-model="cost.sale"
                              data-vv-name="sale"
                              v-validate="'required|decimal:2'"
                              :error-messages="errors.collect('sale')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md12 lg12>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field
                              label="Transferencia"
                              v-model="cost.transfer"
                              data-vv-name="transfer"
                              v-validate="'required|decimal:2'"
                              :error-messages="errors.collect('transfer')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field
                              label="Importe"
                              v-model="cost.amount"
                              data-vv-name="amount"
                              v-validate="'required|decimal:2'"
                              :error-messages="errors.collect('amount')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field
                              label="Transporte"
                              v-model="cost.transport"
                              data-vv-name="transport"
                              v-validate="'required|decimal:2'"
                              :error-messages="errors.collect('transport')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md3 lg3>
                            <v-text-field
                              label="IVA"
                              v-model="cost.iva"
                              data-vv-name="iva"
                              v-validate="'required|decimal:2'"
                              :error-messages="errors.collect('iva')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                    <small>Los campos con (*) son obligatorios.</small>
                  </v-card-text>
                  <v-divider class="mt-5"></v-divider>
                  <v-card-actions>
                    <v-btn :disabled="loading" to="/dashboard">
                      Cancelar
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn :loading="loading" @click="updateCosts" >
                      Actualizar
                    </v-btn>
                  </v-card-actions>
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
  import Cost from '../../class/costs/Cost'

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'costs',
    data: () => ({
      success: false,
      loading: false,
      cost: new Cost(),
      dictionary: {
        custom: {
          chilean: {
            required: () => 'Este campo es requerido',
            decimal: 'El campo debe ser numérico y puede contener 2 decimales'
          },
          dollar: {
            required: () => 'Este campo es requerido',
            decimal: 'El campo debe ser numérico y puede contener 2 decimales'
          },
          purchase: {
            required: () => 'Este campo es requerido',
            decimal: 'El campo debe ser numérico y puede contener 2 decimales'
          },
          sale: {
            required: () => 'Este campo es requerido',
            decimal: 'El campo debe ser numérico y puede contener 2 decimales'
          },
          transfer: {
            required: () => 'Este campo es requerido',
            decimal: 'El campo debe ser numérico y puede contener 2 decimales'
          },
          amount: {
            required: () => 'Este campo es requerido',
            decimal: 'El campo debe ser numérico y puede contener 2 decimales'
          },
          transport: {
            required: () => 'Este campo es requerido',
            decimal: 'El campo debe ser numérico y puede contener 2 decimales'
          },
          iva: {
            required: () => 'Este campo es requerido',
            decimal: 'El campo debe ser numérico y puede contener 2 decimales'
          }
        }
      }
    }),

    created() {
      this.showCosts()
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      showCosts() {
        axios.get('/api/costs')
        .then(response => {
          if (response.data.success) {
          	this.cost = response.data.data
          }
          this.success = true
        })
      },

      updateCosts() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            axios.put(`/api/cost/${this.cost.id}`, this.cost)
            .then((response) => {
              if (response.data.success) {
                this.loading = false
                this.$snotify.simple(response.data.message, 'Felicidades')
              }
            })
            .catch((error) => {
              this.loading = false
            })
          }
        })
      },

      reset () {
        this.$refs.form.reset()
      } 
    }
  }
</script>


