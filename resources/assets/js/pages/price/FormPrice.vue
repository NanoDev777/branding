<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout row wrap>
      <v-flex d-flex xs12 sm12 md12>
        <v-card>
          <v-card-title primary-title>
            <h3 class="headline mb-0">{{ addSubtitle }}</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-text>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md5 lg5>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Cantidad"
                              v-model="price.quantity"
                              data-vv-name="quantity"
                              v-validate="'required|decimal:2|max:8'"
                              :error-messages="errors.collect('quantity')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Logo"
                              v-model="price.logo"
                              data-vv-name="logo"
                              v-validate="'required|decimal:2|max:8'"
                              :error-messages="errors.collect('logo')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Utilidad"
                              v-model="price.utility"
                              data-vv-name="utility"
                              v-validate="'required|decimal:2|max:8'"
                              :error-messages="errors.collect('utility')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                    <small>Los campos con (*) son obligatorios.</small>
                  </v-card-text>
                  <v-divider class="mt-5"></v-divider>
                  <v-card-actions>
                    <v-btn :disabled="loading" to="/prices">
                      Cancelar
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn @click="submit" :loading="loading">
                      {{ id == null ? 'Registrar' : 'Actualizar' }}
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
  import Price from '../../class/price/Price'

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'form-price',
    data(){
      return {
        succes: false,
        loading: false,
        price: new Price(),
        id: this.$route.params.id,
        dictionary: {
          custom: {
            quantity: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            logo: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            utility: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            }
          }
        }
      }
    },

    computed: {
      addSubtitle () {
        if(this.id) {
          return 'Editar Precio'
        }
        return 'Registrar Precio'
      }
    },

    created() {
      if (this.id) {
        this.showPrice()
      }else{
        this.success = true
      }
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      showPrice() {
        axios.get(`/api/price/${this.id}`)
        .then(response => {
          if (response.data.success) {
            this.price = response.data.data
          }
          this.success = true
        })
      },

      submit() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            if(this.id) {
              this._save = axios.put(`/api/price/${this.id}`, this.price)
            } else {
              this._save = axios.post('/api/create-price', this.price)
            }
            this._save
            .then(response => {
              if(response.data.success) {
                this.$router.push('/prices')
                this.$snotify.simple(response.data.message, 'Felicidades')
              }
              this.loading = false
            })
            .catch(error =>{
              this.loading = false
            })
          }
        })
      }
    }
  }
</script>


