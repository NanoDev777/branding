<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout row wrap>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
          <v-card-title primary-title>
            <h3 class="headline mb-0">{{ addSubtitle }}</h3>
            <v-spacer></v-spacer>
            <create-color></create-color>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-text>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md5 lg5>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field
                              label="Código *"
                              v-model="product.code"
                              data-vv-name="code"
                              v-validate="'required|max:30'"
                              :error-messages="errors.collect('code')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field
                              label="Nombre *"
                              v-model="product.name"
                              data-vv-name="name"
                              v-validate="'required|max:120'"
                              :error-messages="errors.collect('name')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Descripción *"
                              v-model="product.description"
                              multi-line
                              data-vv-name="description"
                              v-validate="'required'"
                              :error-messages="errors.collect('description')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field
                              label="Anchura *"
                              v-model="product.width"
                              placeholder="0.00"
                              data-vv-name="width"
                              v-validate="'required|decimal:2|max:8'"
                              :error-messages="errors.collect('width')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field
                              label="Altura *"
                              v-model="product.height"
                              placeholder="0.00"
                              data-vv-name="height"
                              v-validate="'required|decimal:2|max:8'"
                              :error-messages="errors.collect('height')"
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field
                              label="Grosor *"
                              v-model="product.thickness"
                              placeholder="0.00"
                              data-vv-name="thickness"
                              v-validate="'required|decimal:2|max:8'"
                              :error-messages="errors.collect('thickness')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field
                              label="Peso *"
                              v-model="product.weight"
                              placeholder="0.00"
                              data-vv-name="weight"
                              v-validate="'required|decimal:2|max:8'"
                              :error-messages="errors.collect('weight')"
                            ></v-text-field>
                          </v-flex>
                        </v-layout>  
                      </v-flex>
                      <v-spacer></v-spacer>
                      <v-flex xs12 sm12 md6 lg6>
                        <v-layout row wrap>
                          <v-flex xs12>
                            <v-select
                              :items="categories"
                              v-model="product.category_id"
                              label="Categoría *"
                              item-text="name"
                              item-value="id"
                              single-line
                              data-vv-name="category"
                              v-validate="'required'"
                              :error-messages="errors.collect('category')"
                            ></v-select>
                          </v-flex>
                          <v-flex xs12>
                            <b>{{ select }}</b>
                            <v-select
                              label="Colores *"
                              :items="colors"
                              v-model="select"
                              multiple
                              item-text="name"
                              item-value="id"
                              chips
                              hint="Si el color que decea no esta en la lista, agrege uno nuevo pulsando el boton azul en la parte superior."
                              persistent-hint
                              data-vv-name="colors"
                              v-validate="'required'"
                              :error-messages="errors.collect('colors')"
                            ></v-select>
                          </v-flex>
                          <v-flex xs12 v-show="product.items.length > 0">
                            <v-data-table
                              :headers="headers"
                              :items="product.items"
                              hide-actions
                              class="elevation-1"
                            >
                              <template slot="items" slot-scope="props">
                                <td>{{ props.item.name }}</td>
                                <td class="text-xs-right">
                                <v-layout row wrap>
                                  <v-flex md2><v-checkbox v-model="props.item.size.value" label="L" value="L"></v-checkbox></v-flex>
                                  <v-flex md2><v-checkbox v-model="props.item.size.value" label="M" value="M"></v-checkbox></v-flex> 
                                  <v-flex md2><v-checkbox v-model="props.item.size.value" label="S" value="S"></v-checkbox></v-flex>
                                  <v-flex md2><v-checkbox v-model="props.item.size.value" label="XL" value="XL"></v-checkbox></v-flex>
                                  <v-flex md2><v-checkbox v-model="props.item.size.value" label="XXL" value="XXL"></v-checkbox></v-flex>
                                  </v-layout>
                                </td>
                              </template>
                            </v-data-table>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                    <small>Los campos con (*) son obligatorios.</small>
                  </v-card-text>
                  <v-divider class="mt-5"></v-divider>
                  <v-card-actions>
                    <v-btn :disabled="loading" flat to="/products">Cancelar</v-btn>
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
  import { mapGetters } from 'vuex'
  import Colors from '../../components/Colors.vue'
  import Product from '../../class/product/Product'
  import CategoryService from '../../class/category/CategoryService'

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'form-product',
    components: {
      'create-color' : Colors
    },
    data () {
      return {
        product: new Product(),
        success: false,
        loading: false,
        categories: [],
        id: this.$route.params.id,
        select: [],
        dictionary: {
          custom: {
            code: {
              required: () => 'Este campo es requerido',
              max: 'Este campo debe tener un máximo de 30 caracteres'
            },
            name: {
              required: () => 'Este campo es requerido',
              max: 'Este campo debe tener un máximo de 120 caracteres'
            },
            description: {
              required: () => 'Este campo es requerido',
            },
            height: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            width: {
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
            category: {
              required: () => 'Este campo es requerido'
            },
            colors: {
              required: () => 'Este campo es requerido'
            }
          }
        },
        headers: [
          {
            text: 'Color',
            align: 'left',
            sortable: false,
            value: 'color'
          },
          { text: 'Tamaño', value: 'tamaño', align: 'center' }
        ] 
      }  
    },

    computed: {
      addSubtitle () {
        if(this.id) {
          return 'Editar Producto'
        }
        return 'Registrar Producto'
      },
      ...mapGetters([
        'colors'
      ])
    },

    watch: {
      select (v) {
        let newItem = v.filter(x => !this.product.items.find(o => o.id === x))
        let deleteItem = this.product.items.filter(x => !v.find(o => o === x.id)).map(o=>o.id)
        let pushItem = this.colors.filter(o => newItem.find(x => x === o.id)).map((obj) => { 
          let rObj = {}
          rObj['id'] = obj.id
          rObj['name'] = obj.name
          rObj['size'] = { value: []}
          return rObj
        })
        pushItem.forEach((obj) => this.product.items.push(obj))
        let index = this.product.items.findIndex(x => deleteItem.find(o => o === x.id))
        if (index > -1){
          this.product.items.splice(index, 1)
        }
      }
    },

    created () {
      let categories = new CategoryService(axios.get('/api/list-categories'))
      categories.list()
      .then(categories => {
        this.categories = categories.list
      })
      this.$store.dispatch("getColors")

      if (this.id) {
        this.showProduct()
      }else{
        this.success = true
      }
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      showProduct() {
        axios.get(`/api/product/${this.id}`)
        .then(response => {
          if (response.data.success) {
            this.product.code = response.data.data.code
            this.product.name = response.data.data.name
            this.product.description = response.data.data.description
            this.product.width = response.data.data.width
            this.product.height = response.data.data.height
            this.product.thickness = response.data.data.thickness
            this.product.weight = response.data.data.weight
            this.product.category_id = response.data.data.category_id
            let colors = response.data.data.colors

            colors.forEach( (obj) => this.select.push(obj.id))

            this.product.items = colors.map((obj) => { 
              let rObj = {}
              rObj['id'] = obj.id
              rObj['name'] = obj.name
              let array
              if (obj.pivot['size'] == 'S/T') {
                array = []
              }else {
                array = obj.pivot['size'].split(",")
              }
              rObj['size'] = { value: array }
              return rObj
            })
            this.success = true
          }
        })
      },

      submit () {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            if(this.id) {
              this._save = axios.put(`/api/product/${this.id}`, this.product)
            } else {
              this._save = axios.post('/api/create-product', this.product)
            }
            this._save
            .then(response => {
              if(response.data.success) {
                this.$router.push(`/products/${response.data.id}`)
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


