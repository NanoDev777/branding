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
                              label="Nombre"
                              v-model="category.name"
                              data-vv-name="name"
                              v-validate="'required|max:120'"
                              :error-messages="errors.collect('name')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Descripción"
                              v-model="category.description"
                              multi-line
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                    <small>Los campos con (*) son obligatorios.</small>
                  </v-card-text>
                  <v-divider class="mt-5"></v-divider>
                  <v-card-actions>
                    <v-btn :disabled="loading" to="/categories">
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
  import Category from '../../class/category/Category'

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'form-category',
    data() {
      return {
        succes: false,
        loading: false,
        category: new Category(),
        id: this.$route.params.id,
        dictionary: {
          custom: {
            name: {
              required: () => 'Este campo es requerido',
              max: 'Este campo debe tener un máximo de 120 caracteres'
            }
          }
        }
      }
    },

    computed: {
      addSubtitle () {
        if(this.id) {
          return 'Editar Categoría'
        }
        return 'Registrar Categoría'
      }
    },

    created() {
      if (this.id) {
        this.showCategory()
      }else{
        this.success = true
      }
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      showCategory() {
        axios.get(`/api/category/${this.id}`)
        .then(response => {
          if (response.data.success) {
            this.category = response.data.data
          }
          this.success = true
        })
      },

      submit() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            if(this.id) {
              this._save = axios.put(`/api/category/${this.id}`, this.category)
            } else {
              this._save = axios.post('/api/create-category', this.category)
            }
            this._save
            .then(response => {
              if(response.data.success) {
                this.$router.push('/categories')
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


