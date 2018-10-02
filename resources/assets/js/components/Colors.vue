<template>
  <v-dialog v-model="dialog" persistent max-width="500px">
    <v-btn fab dark small color="primary" slot="activator"><v-icon dark>edit</v-icon></v-btn>
    <v-card>
      <v-card-title>
        <span class="headline">Nuevo Color</span>
      </v-card-title>
      <v-card-text>
        <v-container grid-list-md>
          <v-layout wrap>
            <v-flex xs12>
              <v-form ref="form" lazy-validation>
                <v-text-field 
                  label="Nombre"
                  v-model="form.name"
                  :rules="nameRules" 
                  :counter="30"
                  required>
                </v-text-field>
              </v-form>
            </v-flex>
          </v-layout>
        </v-container>
        <small>(*) Indica que el campo es necesario</small>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn :disabled="loader" color="blue darken-1" flat @click.native="dialog = false">Cancelar</v-btn>
        <v-btn :loading="loader" color="blue darken-1" flat @click="submit">Guardar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
  export default {
    name: 'colors',
    data: () => ({
      dialog: false,
      loader: false,
      form: {
        name: ''
      },
      nameRules: [
        v => !!v || 'El nombre es requerido',
        v => (v && v.length <= 30) || 'El nombre no debe superar los 30 caracteres'
      ]
    }),

    methods: {
      submit () {
        if (this.$refs.form.validate()) {
          this.loader = true
          axios.post('/api/create-color', this.form)
          .then(response => {
            this.$store.dispatch('addColor', response.data.data)
              .then(() => {
                if (response.data.success) {
                  this.reset()
                  this.$snotify.simple(response.data.message, 'Felicidades')
                }
                this.dialog = false
                this.loader = false
              })
          })
          .catch(e => {
            this.dialog = false
            this.loader = false
          })
        }
      },
      reset () {
        this.$refs.form.reset()
      }
    }
  }
</script>