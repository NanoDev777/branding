<template>
  <v-container fluid grid-list-md id="theme">
    <v-layout row wrap>
      <v-flex d-flex xs12 sm12 md12>
        <v-progress-circular indeterminate :size="70" :width="7" color="grey darken-1" v-if="!loader"></v-progress-circular>
        <v-card v-show="loader">
          <v-card-title primary-title>
            <h3 class="headline mb-0">Editar producto</h3>
            <v-spacer></v-spacer>
            <create-color></create-color>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-alert outline color="error" icon="warning" :value="true" v-if="error">
                  {{ msg }}
                </v-alert>
                <v-card v-show="!error">
                  <v-progress-linear :indeterminate="true" color="yellow accent-3" v-if="loading">
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
                    <v-btn :disabled="loading" :to="{ name: 'Product', params: { id: id }}">
                      Cancelar
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn @click="updateProduct" :loading="loading">
                      Actualizar
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
    name: 'editproduct',
    props: ["id"],
    components: {
      'create-color' : Colors
    },
    data: () => ({
      loader: false,
      loading: false,
      error: false,
      msg:'',
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
      flag: false,
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
        if (!this.flag) {
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
          return
        }
        this.flag = false
      }
    },

    created () {
      if (this.colors.length === 0)
        this.$store.dispatch("getColors")

      this.showProduct()
    },

    methods: {
      showProduct() {
        axios.get('/api/product/' + this.id)
        .then(response => {
          this.form.code = response.data.data.code
          this.form.name = response.data.data.name
          this.form.description = response.data.data.description
          this.form.size = response.data.data.size
          this.form.width = response.data.data.width
          this.form.height = response.data.data.height
          this.form.thickness = response.data.data.thickness
          this.form.weight = response.data.data.weight
          this.form.price = response.data.data.price
          this.form.category = response.data.data.category_id

          let colors = response.data.data.colors

          colors.forEach( (obj) => this.select.push(obj.id))

          this.form.items = colors.map((obj) => { 
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
          this.flag = true
          this.loader = true
        })
        .catch(error => {
          this.loader = true
          this.error = true
          this.msg = error.response.data.error
        })
      },

      updateProduct() {
        this.loading = true
        axios.put(`/api/product/${this.id}`, this.form)
        .then((response) => {
          this.$store.dispatch("updateProduct", response.data.data)
          .then(()=>{
            this.loading = false
            this.$snotify.success(response.data.msg, 'Felicidades')
          })
        })
        .catch((error) => {
          this.loading = false
          this.$snotify.error(error.response.data.msg, 'Error')
        })
      },

      reset () {
        this.$refs.form.reset()
      } 
    }
  }
</script>


