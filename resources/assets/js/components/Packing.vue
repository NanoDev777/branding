<template>
  <v-card>
    <v-card-text>
      <h3 class="headline mb-0">Datos de Embalaje</h3>
      <v-layout row wrap>
        <v-flex xs12 sm12 md2 lg2>
          <v-text-field
            label="Anchura"
            v-model="form.width"
            @focus ="$event.target.select()"
            data-vv-name="width"
            v-validate="'required|decimal:2'"
            :error-messages="errors.collect('width')"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md2 lg2>
          <v-text-field
            label="Altura"
            v-model="form.height"
            @focus ="$event.target.select()"
            data-vv-name="height"
            v-validate="'required|decimal:2'"
            :error-messages="errors.collect('height')"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md2 lg2>
          <v-text-field
            label="Grosor"
            v-model="form.thickness"
            @focus ="$event.target.select()"
            data-vv-name="thickness"
            v-validate="'required|decimal:2'"
            :error-messages="errors.collect('thickness')"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md2 lg2>
          <v-text-field
            label="Peso"
            v-model="form.weight"
            @focus ="$event.target.select()"
            data-vv-name="weight"
            v-validate="'required|decimal:2'"
            :error-messages="errors.collect('weight')"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 sm12 md4 lg4>
          <v-text-field
            label="Caja"
            v-model="form.box"
            @focus ="$event.target.select()"
            data-vv-name="box"
            v-validate="'required'"
            :error-messages="errors.collect('box')"
          ></v-text-field>
        </v-flex>
        <pre>{{ $data }}</pre>
      </v-layout>
    </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn :loading="loading" @click="savePacking">
          {{ form.id == null ? 'Registrar' : 'Actualizar' }}
        </v-btn>
      </v-card-actions>
  </v-card>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'packing',
    props: ['product'],
    data () {
      return {
        loading: false,
        form: {
          id: null,
          width: null,
          height: null,
          thickness: null,
          weight: null,
          box: '',
          product_id: this.product
        },
        dictionary: {
          custom: {
            width: {
              required: () => 'Este campo es requerido',
              numeric: 'Este campo solo puede contener números enteros'
            },
            height: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales'
            },
            thickness: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales'
            },
            weight: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales'
            },
            box: {
              required: () => 'Este campo es requerido'
            }
          }
        }
      }
    },

    computed: {
      ...mapGetters([
        'packing'
      ])
    },

    created() {
      if (this.packing != null) {
        this.form = Object.assign({}, this.packing)
      }
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },
  
    methods: {
      savePacking() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            if (this.form.id != null) {
              this._save = axios.put(`/api/packing/${this.form.id}`, this.form)
            } else {
              this._save = axios.post('/api/create-packing', this.form)
            }
            this._save
            .then(response => {
              if(response.data.success) {
                this.$store.dispatch('getPacking', response.data.data)
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
