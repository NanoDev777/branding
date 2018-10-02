<template>
  <v-content>
    <v-container fluid fill-height>
      <v-layout align-center justify-center>
        <v-flex xs12 sm8 md4>
          <v-card class="elevation-12">
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
                  v-model="form.email"
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
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </v-content>
</template>

<script>
  export default {
    name: 'Login',
    data () {
      return {
        form: {
          email: '',
          password: ''
        },
        visible: true,
        remember: false,
        busy: true,
        alert: false,
        nameRules: [
          (v) => !!v || 'El usuario es requerido',
          (v) => v && v.length <= 50 || 'usuario debe tener menos de 10 caracteres'
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
        let data = { grant_type: 'password', client_id: 2, client_secret: '9kxMviWN76r9j22xuyNGRB81n1vEEpepI02bPDuE',username: this.form.email,password: this.form.password }
        this.alert = false
        if (this.$refs.form.validate()) {
          this.busy = false
          axios.post('api/oauth/token', data)
          .then( response => {
            this.busy = true
            if(response.data.message) {
              this.message = response.data.message
              this.alert = true
            } else {
              console.log(response.data)
              this.$store.dispatch('saveToken', response.data.access_token)
              this.$store.dispatch('saveExpiration', response.data.expires_in + Date.now())
              this.$store.dispatch('getDataUser')
              .then(()=>{
                this.$router.push({ name: 'Home' })
                this.$snotify.simple('Ingreso correctamente al sistema!', 'Bienvenido')
              })
            }
          })
          .catch( error => {
            this.busy = true
            this.alert = true
            this.message = 'El usuario y/o la contraseña son inválidos'
          })
        }
      }
    }
  }
</script>
