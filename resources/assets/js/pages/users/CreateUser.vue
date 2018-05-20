<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card>
          <v-card-title primary-title>
            <h3 class="headline mb-0">Registrar Usuario</h3>
          </v-card-title>
          <v-card-text>
            <v-layout row wrap>
              <v-flex xs12 sm12 md5 lg5>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md12 lg12>
                    <v-text-field
                      label="Nombre De Usuario"
                      v-model="user.name"
                      data-vv-name="name"
                      v-validate="'required'"
                      :error-messages="errors.collect('name')"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md12 lg12>
                    <v-text-field
                      label="Correo Electronico"
                      v-model="user.email"
                      data-vv-name="email"
                      v-validate="'required|email'"
                      :error-messages="errors.collect('email')"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md12 lg12>
                    <v-select
                      :items="profiles"
                      v-model="user.profile_id"
                      data-vv-name="profile"
                      v-validate="'required'"
                      :error-messages="errors.collect('profile')"
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
                      ref="password"
                      name="password"
                      label="Contraseña"
                      data-vv-name="password"
                      min="6"
                      v-validate="'required|min:6|max:100'"
                      v-model="user.password"
                      :error-messages="errors.collect('password')"
                      hint="Debe tener un mínimo de 6 caracteres"
                      prepend-icon="lock"
                      :append-icon="isPasswordVisible ? 'visibility' : 'visibility_off'"
                      :append-icon-cb="() => (isPasswordVisible = !isPasswordVisible)"
                      :type="isPasswordVisible ? 'text' : 'password'"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md12 lg12>
                    <v-text-field
                      label="Confirmación de contraseña"
                      data-vv-name="password_confirmation"
                      target= "password"
                      v-validate="'required|confirmed:password'"
                      v-model="user.password_confirmation"
                      :error-messages="errors.collect('password_confirmation')"
                      prepend-icon="lock"
                      :append-icon="isPasswordVisible2 ? 'visibility' : 'visibility_off'"
                      :append-icon-cb="() => (isPasswordVisible2 = !isPasswordVisible2)"
                      :type="isPasswordVisible2 ? 'text' : 'password'"
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
            <v-btn @click="save" :loading="loading">
              Registrar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
  import ProfileService from '../../class/profile/ProfileService'
  import User from '../../class/user/User'

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'create-user',
    data: () => ({
      user: new User(),
      success: false,
      loading: false,
      profiles: [],
      isPasswordVisible: false,
      isPasswordVisible2: false,
      dictionary: {
        custom: {
          name: {
            required: () => 'Este campo es requerido',
          },
          email: {
            required: () => 'Este campo es requerido',
            email: 'Ingrese un correo electrónico válido'
          },
          profile: {
            required: () => 'Este campo es requerido',
          },
          password: {
            required: () => 'La contraseña no puede estar vacía',
            max: 'El campo de contraseña no puede tener más de 100 caracteres',
            min: 'El campo de contraseña no puede tener menos de 6 caracteres'
          },
          password_confirmation: {
            required: () => 'La confirmación de la contraseña no puede estar vacía',
            confirmed: 'La confirmación de contraseña no coincide'
          }
        }
      }
    }),

    created(){
      axios.get('/api/list-profiles')
      .then(response => {
        this.profiles = response.data.list
        this.success = true
      })
      .catch(error =>{
        this.success = true
      })
    },  

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      save() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            axios.post('/api/create-user', this.user)
            .then(response => {
              if (response.data.success) {
                this.$router.push('/users')
                this.$snotify.simple(response.data.message, 'Felicidades')
              }
              this.loading = false
            })
            .catch(error =>{
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

