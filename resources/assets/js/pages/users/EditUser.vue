<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
          <v-card-title primary-title>
            <h3 class="headline mb-0">Editar Usuario</h3>
          </v-card-title>
          <v-card-text>
            <v-form v-model="valid" ref="form" lazy-validation>
              <v-layout row wrap>
                <v-flex xs12 sm12 md5 lg5>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-text-field
                        label="Nombre De Usuario"
                        v-model="form.name"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-text-field
                        label="Correo Electronico"
                        v-model="form.email"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-select
                        :items="items"
                        v-model="form.perfil"
                        label="Perfil"
                        item-text="description"
                        item-value="id"
                        single-line
                      ></v-select>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-text-field
                        label="Contraseña"
                        v-model="form.pass"
                      ></v-text-field>
                    </v-flex>
                  </v-layout>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-text-field
                        label="Contraseña"
                        v-model="form.password"
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
            <v-btn @click="updateUser" :loading="loading">
              Actualizar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  export default {
    name: 'edit-user',
    props: ["id"],
    data: () => ({
      success: false,
      loading: false,
      valid: true,
      form: {
        name: '',
        email: '',
        perfil: null,
        pass: '',
        password: ''
      },
      items: [
        { id:1, description:'Administrador' },
        { id:2, description:'Vendedor' }
      ],
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
      this.showUser()
    },

    methods: {
      showUser() {
        axios.get(`/api/user/${this.id}`)
        .then(response => {
          if (response.data.success) {
            this.form.name = response.data.data.name
            this.form.email = response.data.data.email
            this.form.perfil = response.data.data.profile_id
            this.success = response.data.success
          }
        })
      },

      updateUser() {
        if (this.$refs.form.validate()) {
          this.loading = true
          axios.put(`/api/user/${this.id}`, this.form)
          .then((response) => {
            this.loading = false
            this.$router.push('/users')
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

