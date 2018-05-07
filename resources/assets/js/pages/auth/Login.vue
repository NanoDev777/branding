<template>
  <v-app id="inspire">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-progress-linear height="4" v-if="!busy" :indeterminate="true" color="yellow accent-3"></v-progress-linear>
              <v-toolbar dark color="grey darken-1">
                <v-toolbar-title>Iniciar Sesión</v-toolbar-title>
                <v-spacer></v-spacer>
              </v-toolbar>
              <v-card-text @keyup.enter="login">
              <v-alert v-if="alert" type="warning" :value="true"><strong>{{ message }}</strong></v-alert>
                <v-form ref="form" lazy-validation>
                  <v-text-field 
                    label="Usuario"
                    prepend-icon="person" 
                    name="login"  
                    type="text"
                    :rules="nameRules"
                    v-model="form.name"
                  ></v-text-field>
                  <v-text-field 
                    label="Contraseña" 
                    prepend-icon="lock" 
                    name="password" 
                    :counter="30"
                    :append-icon="visible ? 'visibility' : 'visibility_off'"
                    :append-icon-cb="() => (visible = !visible)"
                    :type="visible ? 'password' : 'text'"
                    :rules="passwordRules"
                    v-model="form.password"
                  ></v-text-field>
                  <v-checkbox
                    label="Recordarme"
                    color="yellow accent-3"
                    type="checkbox"
                    v-model="remember"
                    value="true"
                  ></v-checkbox>
                  <v-btn block large color="yellow accent-3" @click="login" :disabled="!busy">
                    <strong v-show="busy"><v-icon>input</v-icon> INGRESAR</strong>
                    <v-progress-circular v-show="!busy" indeterminate :width="7"></v-progress-circular> 
                  </v-btn>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <router-link to="home">Olvidaste tu contraseña?</router-link>
              </v-card-actions>
            </v-card>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
  export default {
    name: 'Login',
    data () {
      return {
        form: {
          name: '',
          password: ''
        },
        visible: true,
        remember: false,
        busy: true,
        alert: false,
        nameRules: [
          (v) => !!v || 'El usuario es requerido',
          (v) => v && v.length <= 10 || 'usuario debe tener menos de 10 caracteres'
        ],
        passwordRules: [
          (v) => !!v || 'La contraseña es requerida',
          (v) => v && v.length <= 30 || 'contraseña debe tener menos de 30 caracteres'
        ],
        message: ''
      }
    },
    methods: {
      login () {
        this.alert = false
        if (this.$refs.form.validate()) {
          this.busy = false
          const self = this;
          axios.post('/api/login',this.form)
          .then( response => {
            this.busy = true
            self.$store.dispatch('saveToken', response.data.token)
            self.$store.dispatch('setCurrentUser', response.data.user)
            self.$router.push({ name: 'Home' })
            this.$snotify.success('Ingreso correctamente al sistema!', 'Bienvenido')
          })
          .catch( error => {
            this.busy = true
            this.alert = true
            this.message = error.response.data.error
          })
        }
      }
    }
  }
</script>

<style lang="stylus" scoped>
.progress-linear
  height: 3px
  left: 0
  margin: 0
  position: absolute
  right: 0
  top: 0
  width: 100%
  z-index: 999999
</style>
