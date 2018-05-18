<template>
  <v-card>
    <v-card-text>
                          <h3 class="headline mb-0">Datos de Embalaje</h3>
                          <v-form v-model="valid" ref="form" lazy-validation>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md2 lg2>
                              <v-text-field
                                label="Anchura"
                                v-model="width"
                                @focus ="$event.target.select()"
                                :rules="widthRules"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md2 lg2>
                              <v-text-field
                                label="Altura"
                                v-model="height"
                                @focus ="$event.target.select()"
                                :rules="heightRules"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md2 lg2>
                              <v-text-field
                                label="Grosor"
                                v-model="thickness"
                                @focus ="$event.target.select()"
                                :rules="thicknessRules"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md2 lg2>
                              <v-text-field
                                label="Peso"
                                v-model="weight"
                                @focus ="$event.target.select()"
                                :rules="weightRules"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md4 lg4>
                              <v-text-field
                                label="Caja"
                                v-model="box"
                                @focus ="$event.target.select()"
                                :rules="boxRules"
                                required
                              ></v-text-field>
                            </v-flex>
                          </v-layout>
                          </v-form>
    </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn :loading="loader" @click="goPacking">
          {{ id == null ? 'Registrar' : 'Actualizar' }}
        </v-btn>
      </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: 'packing',
    props: {
      id: {
        
        type: Number
      },
      width: {
        
        type: Number
      },
      height: {
        
        type: Number
      },
      thickness: {
        
        type: Number
      },
      weight: {
        
        type: Number
      },
      box: {
        
        type: String
      },
      product: {
        
        type: Number
      }
    }, 
    data () {
      return {
        valid: true,
        loader: false,
        widthRules: [
          v => !!v || 'Este campo es requerido',
          v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
        ],
        heightRules: [
          v => !!v || 'Este campo es requerido',
          v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
        ],
        thicknessRules: [
          v => !!v || 'Este campo es requerido',
          v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
        ],
        weightRules: [
          v => !!v || 'Este campo es requerido',
          v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
        ],
        boxRules: [
          v => !!v || 'Este campo es requerido'
        ]
      }
    },
  
    methods: {
      goPacking() {
        if (this.packing.id != null) {
          this.updatePacking()
        } else {
          this.createPacking()
        }
      },
      createPacking() {     
        if (this.$refs.form.validate()) {
          this.loader = true
          axios.post('/api/create-packing', this.packing)
          .then((response) => {
            this.loader = false
          })
          .catch((error) => {
          })
        }
      },
      updatePacking() {
        if (this.$refs.form.validate()) {
          this.loader = true
          axios.put(`/api/packing/${this.packing.id}`, this.packing)
          .then((response) => {
          })
          .catch((error) => {
          })
        }
      }
    }
  } 
</script>
