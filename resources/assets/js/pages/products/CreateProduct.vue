<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout row wrap>
      <v-flex d-flex xs12 sm12 md12>
        <v-progress-circular indeterminate :size="70" :width="7" color="grey darken-1" v-if="loading"></v-progress-circular>
        <v-card v-show="!error">
          <v-card-title primary-title>
            <h3 class="headline mb-0">Registrar nuevo producto</h3>
            <v-spacer></v-spacer>
            <create-color></create-color>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-progress-linear :indeterminate="true" color="yellow accent-3" v-if="loader">
                  </v-progress-linear>
                  <v-card-text>
                    <v-form v-model="valid" ref="form" lazy-validation>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md5 lg5>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field
                              label="Código"
                              v-model="form.code"
                              :rules="codeRules"
                              :counter="10"
                              required
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md6 lg6>
                            <v-text-field
                              label="Nombre"
                              v-model="form.name"
                              :rules="nameRules"
                              required
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <v-text-field
                              label="Descripción"
                              v-model="form.description"
                              :rules="descriptionRules"
                              required
                              multi-line
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field
                              label="Anchura"
                              v-model="form.width"
                              placeholder="0.00"
                              :rules="widthRules"
                              required
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field
                              label="Altura"
                              v-model="form.height"
                              placeholder="0.00"
                              :rules="heightRules"
                              required
                            ></v-text-field>
                          </v-flex>
                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field
                              label="Grosor"
                              v-model="form.thickness"
                              placeholder="0.00"
                              :rules="thicknessRules"
                              required
                            ></v-text-field>
                          </v-flex>
                        </v-layout>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md4 lg4>
                            <v-text-field
                              label="Peso"
                              v-model="form.weight"
                              placeholder="0.00"
                              :rules="weightRules"
                              required
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
                              v-model="form.category"
                              label="Categoría"
                              item-text="name"
                              item-value="id"
                              single-line
                              :rules="categoryRules"
                              required
                            ></v-select>
                          </v-flex>
                          <v-flex xs12>
                            <v-select
                              label="Colores*"
                              :items="colors"
                              v-model="select"
                              multiple
                              item-text="name"
                              item-value="id"
                              chips
                              hint="Si el color que decea no esta en la lista, agrege uno nuevo pulsando el boton azul en la parte superior."
                              persistent-hint
                            ></v-select>
                          </v-flex>
                          <v-flex xs12 v-show="form.items.length > 0">
                            <v-data-table
                              :headers="headers"
                              :items="form.items"
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
                    </v-form>
                    <small>Los campos con (*) son obligatorios.</small>
                  </v-card-text>
                  <v-divider class="mt-5"></v-divider>
                  <v-card-actions>
                    <v-btn :disabled="loader" flat to="/products">Cancelar</v-btn>
                    <v-spacer></v-spacer>
                    <v-slide-x-reverse-transition>
                      <v-tooltip left v-if="clear">
                        <v-btn
                          icon
                          @click="reset"
                          slot="activator"
                          class="my-0"
                        >
                          <v-icon>refresh</v-icon>
                        </v-btn>
                        <span>Limpiar formulario</span>
                      </v-tooltip>
                    </v-slide-x-reverse-transition>
                    <v-btn @click="submit" :loading="loader">
                      Registrar
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

  export default {
    name: 'createproduct',
    components: {
      'create-color' : Colors
    },
    data: () => ({
      loading: true,
      loader: false,
      error: true,
      clear: false,
      valid: true,
      select: [],
      form: {
        code: '',
        name: '',
        description: '',
        size: '',
        width: null,
        height: null,
        thickness: null,
        weight: null,
        category: null,
        items: []
      },
      codeRules: [
        v => !!v || 'El código es requerido',
        v => (v && v.length <= 10) || 'No debe sobrepasar los 10 caracteres'
      ],
      nameRules: [
        v => !!v || 'El nombre es requerido',
      ],
      descriptionRules: [
        v => !!v || 'La descripción es requerida'
      ],
      sizeRules: [
        v => !!v || 'El tamaño es requerido',
        v => (v && v.length <= 10) || 'No debe sobrepasar los 10 caracteres'
      ],
      widthRules: [
        v => !!v || 'La anchura es requerida',
        v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
      ],
      heightRules: [
        v => !!v || 'La altura es requerida',
        v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
      ],
      thicknessRules: [
        v => !!v || 'El grosor es requerido',
        v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
      ],
      weightRules: [
        v => !!v || 'El peso es requerido',
        v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
      ],
      priceRules: [
        v => /^[0-9]+([.])?([0-9]+)?$/.test(v) || 'Escriba el valor con el formato: 0.00'
      ],
      categoryRules: [
        v => !!v || 'La categoría es requerida'
      ],
      colorsRules: [
        v => !!v || 'Los colores son requeridos'
      ],
      headers: [
        {
          text: 'Color',
          align: 'left',
          sortable: false,
          value: 'color'
        },
        { text: 'Tamaño', value: 'tamaño', align: 'center' }
      ] 
    }),

    computed: {
      ...mapGetters([
        'categories',
        'colors'
      ])
    },

    watch: {
      select (v) {
        let newItem = v.filter(x => !this.form.items.find(o => o.id === x))
        let deleteItem = this.form.items.filter(x => !v.find(o => o === x.id)).map(o=>o.id)
        let pushItem = this.colors.filter(o => newItem.find(x => x === o.id)).map((obj) => { 
          let rObj = {}
          rObj['id'] = obj.id
          rObj['name'] = obj.name
          rObj['size'] = { value: []}
          return rObj
        })
        pushItem.forEach((obj) => this.form.items.push(obj))
        let index = this.form.items.findIndex(x => deleteItem.find(o => o === x.id))
        if (index > -1){
          this.form.items.splice(index, 1)
        }
      }
    },

    created () {
      this.$store.dispatch("getCategories")
      .then(() => {
        this.$store.dispatch("getColors")
        this.error = false
        this.loading = false
      }).catch((error) => {
        this.loading = false
        this.$snotify.error(error, 'Error')
      })
    },

    methods: {
      submit () {
        this.clear = true
        if (this.$refs.form.validate()) {
          this.loader = true
          this.clear = false
          axios.post('/api/create-product', this.form)
          .then((response) => {
            this.form.items = []
            this.$store.dispatch("addProduct", response.data.data)
            .then(()=>{
              this.loader = false
              this.reset()
              this.$snotify.success(response.data.msg, 'Felicidades')
            })
          })
          .catch((error) => {
            this.loader = false
            this.clear = true
            this.$snotify.error(error.response.data.msg, 'Error')
          })
        }
      },
      reset () {
        this.$refs.form.reset()
      }
    }
  }
</script>


