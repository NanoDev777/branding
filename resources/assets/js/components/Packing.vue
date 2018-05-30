<template>
  <v-card>
    <v-card-text>
      <h3 class="headline mb-0">Datos de Embalaje</h3>
      <v-layout row wrap>
        <v-flex xs12 sm12 md2 lg2>
          <v-text-field
            label="Anchura"
            v-model="packing.width"
            @focus ="$event.target.select()"
            data-vv-name="width"
            v-validate="'required|decimal:2|max:8'"
            :error-messages="errors.collect('width')"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md2 lg2>
          <v-text-field
            label="Altura"
            v-model="packing.height"
            @focus ="$event.target.select()"
            data-vv-name="height"
            v-validate="'required|decimal:2|max:8'"
            :error-messages="errors.collect('height')"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md2 lg2>
          <v-text-field
            label="Grosor"
            v-model="packing.thickness"
            @focus ="$event.target.select()"
            data-vv-name="thickness"
            v-validate="'required|decimal:2|max:8'"
            :error-messages="errors.collect('thickness')"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md2 lg2>
          <v-text-field
            label="Peso"
            v-model="packing.weight"
            @focus ="$event.target.select()"
            data-vv-name="weight"
            v-validate="'required|decimal:2|max:8'"
            :error-messages="errors.collect('weight')"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md4 lg4>
          <v-text-field
            label="Caja"
            v-model="packing.box"
            @focus ="$event.target.select()"
            data-vv-name="box"
            v-validate="'required|max:30'"
            :error-messages="errors.collect('box')"
          ></v-text-field>
        </v-flex>
      </v-layout>
    </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn :loading="loading" @click="submit">
          {{ packing.id == null ? 'Registrar' : 'Actualizar' }}
        </v-btn>
      </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'packing',
    props: {
      list: {
        required: true,
        type: Object
      }
    },
    data () {
      return {
        loading: false,
        dictionary: {
          custom: {
            width: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            height: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            thickness: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            weight: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            box: {
              required: () => 'Este campo es requerido',
              max: 'Este campo debe tener un máximo de 30 caracteres'
            }
          }
        }
      }
    },

    computed: {
      packing(){
        return this.list
      }
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },
  
    methods: {
      submit() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            if (this.packing.id != null) {
              this._save = axios.put(`/api/packing/${this.packing.id}`, this.packing)
            } else {
              this._save = axios.post('/api/create-packing', this.packing)
            }
            this._save
            .then(response => {
              if(response.data.success) {
                this.$emit('data-received',response.data.data)
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
