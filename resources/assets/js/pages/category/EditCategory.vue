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
                              label="Nombre"
                              v-model="form.name"
                              :rules="nameRules"
                              required
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Descripción"
                              v-model="form.description"
                              multi-line
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
                    <v-btn :disabled="loading" to="/categories">
                      Cancelar
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn @click="updateCategory" :loading="loading">
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
    name: 'edit-category',
    props: ["id"],
    data: () => ({
      success: false,
      loading: false,
      valid: true,
      form: {
        name: '',
        description: '',
      },
      flag: false,
      nameRules: [
        v => !!v || 'Este campo es requerido'
      ]
    }),

    created () {
      this.showCategories()
    },

    methods: {
      showCategories() {
        axios.get(`/api/category/${this.id}`)
        .then(response => {
          if (response.data.success) {
            this.form.name = response.data.data.name
            this.form.description = response.data.data.description
            this.success = response.data.success
          }
        })
      },

      updateCategory() {
        if (this.$refs.form.validate()) {
          this.loading = true
          axios.put(`/api/category/${this.id}`, this.form)
          .then((response) => {
            this.loading = false
            this.$router.push('/categories')
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


