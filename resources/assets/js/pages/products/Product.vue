<template>
  <v-container fluid grid-list-md>
    <v-layout row wrap>
      <v-flex d-flex xs12 sm12 md12>
        <v-progress-circular indeterminate :size="70" :width="7" color="grey darken-1" v-if="loading"></v-progress-circular>
        <v-card v-show="!loading">
          <v-dialog v-model="dialog" persistent max-width="290">
            <v-card>
              <v-card-title class="headline">Eliminar Producto</v-card-title>
              <v-card-text>Realmente desea eliminar los datos de este producto?</v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click.native="dialog = false">Cancelar</v-btn>
                <v-btn color="blue darken-1" flat @click.native="dialog = false">Aceptar</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-tabs
            fixed-tabs
            v-model="active"
            slider-color="yellow accent-3"
          >
            <v-tab ripple>
              Detalles
            </v-tab>
            <v-tab-item>
              <v-card flat>
                <v-container fluid>
                  <v-layout row wrap>
                    <v-flex xs12 sm12 md6 lg6>
                      <v-card>
                        <v-carousel>
                          <v-carousel-item
                            v-for="(item,i) in images"
                            :key="i"
                            :src="'/img/'+item.image">
                            <div title="Eliminar Imagen">
                              <v-btn fab flat small color="red accent-3" @click="click(item.id)" :loading="imgloader">
                                <v-icon>delete_forever</v-icon>
                              </v-btn>
                            </div>
                          </v-carousel-item>
                        </v-carousel>
                      </v-card>
                      <br><hr>
                      <v-card>
                        <b>CÓDIGO: {{ code }}</b>
                      </v-card>
                    </v-flex>
                  <v-spacer></v-spacer>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-card>
                      <v-card-title primary-title>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <h3 class="headline mb-0">{{ name }}</h3>
                            <div>{{ description }}</div>
                            <br>
                            <div><span class="grey--text">Especificaciones Artículo</span></div>
                            <div><span>{{ height }} / {{ width }} / {{ thickness }} cm | {{ weight }} gr.</span></div>
                            <br>
                            <div><span class="grey--text">Especificaciones Embalaje</span></div>
                            <div v-show="packing.id != null">
                              <span>{{ packing.height }} / {{ packing.width }} / {{ packing.thickness }} cm | {{ packing.weight }} kg.</span>
                              <v-icon title="Medidas">archive</v-icon><span>{{ packing.box }}</span>
                            </div>
                            <br>
                            <hr>
                            <v-layout>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-data-table
                                  :headers="headerColor"
                                  :items="colors"
                                  hide-actions
                                  class="elevation-1"
                                >
                                  <template slot="items" slot-scope="props">
                                    <td>{{ props.item.name }}</td>
                                    <td>{{ props.item.size }} </td>
                                  </template>
                                </v-data-table>
                              </v-flex>
                            </v-layout>
                            <v-layout>
                              <v-flex xs12 sm12 md12 lg12>
                                <v-data-table
                                  :headers="headerPrice"
                                  :items="colors"
                                  hide-actions
                                  class="elevation-1"
                                >
                                  <template slot="items" slot-scope="props">
                                    <td>{{ props.item.name }}</td>
                                    <td>{{ props.item.size }} </td>
                                  </template>
                                </v-data-table>
                              </v-flex>
                            </v-layout>
                          </v-flex>
                        </v-layout>
                      </v-card-title>
                      <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn flat color="yellow accent-3" :to="{ name: 'EditProduct', params: { id: id }}">
                          <v-icon>edit</v-icon>
                        </v-btn>            
                        <v-btn flat color="red accent-3" slot="activator" @click.native="dialog = true">
                          <v-icon>delete</v-icon>
                        </v-btn>
                      </v-card-actions>
                    </v-card>
                  </v-flex>
                </v-layout>
                </v-container>
              </v-card>
            </v-tab-item>
            <v-tab ripple>
              Imagenes y Embalaje
            </v-tab>
            <v-tab-item>
              <v-card flat>
                <v-container fluid>
                  <v-layout>
                    <v-flex xs12 sm12 md12 lg12>
                      <v-card>
                        <v-card-text>
                          <h3 class="headline mb-0">Datos de Embalaje</h3>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md2 lg2>
                              <v-text-field
                                label="Anchura"
                                v-model="packing.width"
                                @focus ="$event.target.select()"
                                :rules="widthRules"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md2 lg2>
                              <v-text-field
                                label="Altura"
                                v-model="packing.height"
                                @focus ="$event.target.select()"
                                :rules="heightRules"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md2 lg2>
                              <v-text-field
                                label="Grosor"
                                v-model="packing.thickness"
                                @focus ="$event.target.select()"
                                :rules="thicknessRules"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md2 lg2>
                              <v-text-field
                                label="Peso"
                                v-model="packing.weight"
                                @focus ="$event.target.select()"
                                :rules="weightRules"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md4 lg4>
                              <v-text-field
                                label="Caja"
                                v-model="packing.box"
                                @focus ="$event.target.select()"
                                :rules="boxRules"
                                required
                              ></v-text-field>
                            </v-flex>
                          </v-layout>
                        </v-card-text>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn :loading="loader" @click="goPacking">
                            {{ packing.id == null ? 'Registrar' : 'Actualizar' }}
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card>
              <v-card flat>
                <v-container fluid>
                  <v-layout>
                    <v-flex xs12 sm12 md12 lg12>
                      <file-input :product="id" v-on:data-received='handler'></file-input>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card>
            </v-tab-item>
          </v-tabs>
        </v-card>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
import FileInput from '../../components/FileInput.vue'

  export default {
    name: 'product',
    props: ["id"],
    components: {
      'file-input' : FileInput
    },
    data () {
      return {
        active: null,
        loading: true,
        loader: false,
        dialog: false,
        imgloader: false,
        code: null,
        name: '',
        description: '',
        height: null,
        width: null,
        thickness: null,
        weight:null,
        colors: [],
        images: [],
        packing: {
          id: null,
          width: null,
          height: null,
          thickness: null,
          weight: null,
          box: '',
          product: parseInt(this.id)
        },
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
        ],
        headerColor: [
          {
            text: 'Color',
            align: 'left',
            sortable: false,
            value: 'color'
          },
          { text: 'Talla', value: 'talla', align: 'left' }
        ],
        headerPrice: [
          {
            text: 'Unidades',
            align: 'left',
            sortable: false,
            value: 'unidades'
          },
          { text: 'Precio', value: 'price', align: 'left' }
        ]
      }
    },
    computed: {
    },
    created() {
      this.showProduct()
    },
    methods: {
      click(id) {
        let r = confirm('Realmente desea eliminar esta imagen?')
        if (r) {
          this.imgloader = true
          axios.delete('/api/image/'+id)
          .then((response) => {
            let index = this.images.findIndex(x => x.id === id)
            this.images.splice(index, 1)
            this.imgloader = false
            this.$snotify.success(response.data.response, 'Felicidades')
          })
          .catch((error) => {
            this.imgloader = false
            this.$snotify.error(error.response.data.error, 'Error')
          })
        }
      },
      showProduct() {
        axios.get('/api/product/' + this.id)
        .then(response => {
          this.code = response.data.data.code
          this.name = response.data.data.name
          this.description = response.data.data.description
          this.height = response.data.data.height
          this.width = response.data.data.width
          this.thickness = response.data.data.thickness
          this.weight = response.data.data.weight
          this.images = response.data.data.images.map(({id, image}) => ({id, image}))
          if (response.data.data.packing !== null) {
            this.packing.id = response.data.data.packing.id
            this.packing.width = response.data.data.packing.width
            this.packing.height = response.data.data.packing.height
            this.packing.thickness = response.data.data.packing.thickness
            this.packing.weight = response.data.data.packing.weight
            this.packing.box = response.data.data.packing.box
          }
          let colors = response.data.data.colors
          this.colors = colors.map((obj) => { 
            let rObj = {}
            rObj['name'] = obj.name
            rObj['size'] = obj.pivot['size']
            return rObj
          })
          this.loading = false
        })
        .catch(error => {
          this.loading = false
          this.$snotify.error(error.response.data.error, 'Error')
        })
      },
      goPacking() {
        if (this.packing.id != null) {
          this.updatePacking()
        } else {
          this.createPacking()
        }
      },
      createPacking() {
        this.loader = true
        axios.post('/api/create-packing', this.packing)
        .then((response) => {
          this.packing.id = response.data.data.id
          this.packing.width = response.data.data.width
          this.packing.height = response.data.data.height
          this.packing.thickness = response.data.data.thickness
          this.packing.weight = response.data.data.weight
          this.packing.box = response.data.data.box
          this.$snotify.success('Registrado correctamente!', 'Felicidades')
          this.loader = false
        })
        .catch((error) => {
          this.loader = false
          this.$snotify.error(error.response.data.msg, 'Error')
        })
      },
      updatePacking() {
        this.loader = true
        axios.put(`/api/packing/${this.packing.id}`, this.packing)
        .then((response) => {
          this.packing.width = response.data.data.width
          this.packing.height = response.data.data.height
          this.packing.thickness = response.data.data.thickness
          this.packing.weight = response.data.data.weight
          this.packing.box = response.data.data.box
          this.$snotify.success('Editado correctamente!', 'Felicidades')
          this.loader = false
        })
        .catch((error) => {
          this.loader = false
          this.$snotify.error(error.response.data.msg, 'Error')
        })
      },
      handler(data) {
        delete data.updated_at
        delete data.created_at
        delete data.product_id
        this.images.unshift(data)
      }
    }
  } 
</script>
