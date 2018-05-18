<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout row wrap>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
          <v-card-title primary-title>
            <h3 class="headline mb-0">Editar categoría</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-text>
                    <v-form v-model="valid" ref="form" lazy-validation>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md5 lg5>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Cantidad"
                              v-model="form.quantity"
                              :rules="quantityRules"
                              required
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Logo"
                              v-model="form.logo"
                              :rules="logoRules"
                              required
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Utilidad"
                              v-model="form.utility"
                              :rules="utilityRules"
                              required
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                    </v-form>
                    <small>Los campos con (*) son obligatorios.</small>
                  </v-card-text>
                  <v-divider class="mt-5"></v-divider>
                  <v-card-actions>
                    <v-btn :disabled="loading" to="/prices">
                      Cancelar
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn @click="updatePrice" :loading="loading">
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
  export default {
    name: 'edit-price',
    props: ["id"],
    data: () => ({
      success: false,
      loading: false,
      valid: true,
      form: {
        quantity: '',
        logo: '',
        utility: ''
      },
      quantityRules: [
        v => !!v || 'Este campo es requerido'
      ],
      logoRules: [
        v => !!v || 'Este campo es requerido'
      ],
      utilityRules: [
        v => !!v || 'Este campo es requerido'
      ]
    }),

    created () {
      this.showPrice()
    },

    methods: {
      showPrice() {
        axios.get(`/api/price/${this.id}`)
        .then(response => {
          if (response.data.success) {
            this.form.quantity = response.data.data.quantity
            this.form.logo = response.data.data.logo
            this.form.utility = response.data.data.utility
            this.success = response.data.success
          }
        })
      },

      updatePrice() {
        if (this.$refs.form.validate()) {
          this.loading = true
          axios.put(`/api/price/${this.id}`, this.form)
          .then((response) => {
            this.loading = false
            this.$router.push('/prices')
            this.$snotify.success(response.data.message, 'Felicidades')
          })
          .catch((error) => {
            this.loading = false
          })
        }
      },

      reset () {
        this.$refs.form.reset()
      } 
    }
  }
</script>


