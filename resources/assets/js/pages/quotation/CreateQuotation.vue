<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card>
          <v-container fluid>
            <v-layout row wrap>
              <v-flex d-flex xs12 sm12 md12>
                <v-select
                  label="Buscar Productos"
                  autocomplete
                  :loading="loading"
                  multiple
                  cache-items
                  chips
                  :items="items"
                  item-text="code"
                  item-value="id"
                  :search-input.sync="search"
                  v-model="select"
                >
                  <template slot="selection" slot-scope="data">
                    <v-chip
                      close
                      @input="data.parent.selectItem(data.item)"
                      :selected="data.selected"
                      class="chip--select-multi"
                      :key="JSON.stringify(data.item)"
                    >
                    <v-avatar>
                      <img :src="`/img/products/${data.item.image}`">
                    </v-avatar>
                    {{ data.item.code }}
                    </v-chip>
                  </template>
                  <template slot="item" slot-scope="data">
                    <template v-if="typeof data.item !== 'object'">
                      <v-list-tile-content v-text="data.item"></v-list-tile-content>
                    </template>
                    <template v-else>
                      <v-list-tile-avatar>
                        <img :src="`/img/products/${data.item.image}`">
                      </v-list-tile-avatar>
                      <v-list-tile-content>
                        <v-list-tile-title v-html="data.item.code"></v-list-tile-title>
                        <v-list-tile-sub-title v-html="data.item.group"></v-list-tile-sub-title>
                      </v-list-tile-content>
                    </template>
                  </template>
                </v-select>
                <v-btn @click="getDataTable" :loading="loader"><v-icon>list</v-icon></v-btn>
              </v-flex>
            </v-layout>
          </v-container>
          <v-card-title primary-title>
            <h3 class="headline mb-0">Cotizaciones</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout>
                <v-flex xs12 sm12 md12 lg12>
                  <v-alert v-if="alerts.length > 0" :value="true" outline color="error" icon="warning">
                    <strong>Se encontraron los siguientes errores:</strong>
                    <div>
                      <ul>
                        <li v-for="item in alerts">
                          El producto {{ item.name }} con código <strong>{{ item.code }}</strong> necesita una cantidad mínima de <strong>{{ item.min }}</strong> unidades
                        </li>
                      </ul>
                    </div>
                  </v-alert>
                </v-flex>
            </v-layout>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-data-table
                    :headers="headers"
                    :items="quo"
                    :loading="loader"
                    expand
                    class="elevation-1"
                  >
                    <v-progress-linear slot="progress" color="grey" indeterminate></v-progress-linear>
                    <template slot="items" slot-scope="props">
                      <td>
                        {{ props.item.code }}
                        <v-btn @click="props.expanded = !props.expanded" flat icon color="blue-grey lighten-3">
                          <v-icon v-if="!props.expanded">add_circle</v-icon>
                          <v-icon v-else>remove_circle</v-icon>
                        </v-btn>
                      </td>
                      <td>{{ props.item.name }}</td>
                      <td>
                        <v-edit-dialog
                          :return-value.sync="props.item.quantity"
                          large
                          lazy
                          persistent
                          cancel-text="cancelar"
                          save-text="aceptar"
                        >
                        <div>{{ props.item.quantity }}</div>
                        <div slot="input" class="mt-3 title">Cantidad</div>
                        <v-text-field
                          slot="input"
                          label="editar"
                          v-model="props.item.quantity"
                          single-line
                          @focus ="$event.target.select()"
                          data-vv-name="quantity"
                          v-validate="'required|numeric|max:8'"
                          :error-messages="errors.collect('quantity')"
                        ></v-text-field>
                        </v-edit-dialog>
                      </td>
                      <td>
                        <v-edit-dialog
                          :return-value.sync="props.item.extra"
                          large
                          lazy
                          persistent
                          cancel-text="cancelar"
                          save-text="aceptar"
                        >
                        <div>{{ props.item.extra }}</div>
                        <div slot="input" class="mt-3 title">Cantidad</div>
                        <v-text-field
                          slot="input"
                          label="editar"
                          v-model="props.item.extra"
                          single-line
                          @focus ="$event.target.select()"
                          data-vv-name="extra"
                          v-validate="'required|decimal:2|max:8'"
                          :error-messages="errors.collect('extra')"
                        ></v-text-field>
                        </v-edit-dialog>
                      </td>
                      <td width="8%">
                        <v-select
                          :items="props.item.images"
                          v-model="props.item.url"
                          item-value="id"
                          single-line
                          chips
                          max-height="auto"
                          data-vv-name="image"
                          v-validate="'required'"
                          :error-messages="errors.collect('image')"
                        >
                          <template slot="selection" slot-scope="data">
                            <v-chip
                              @input="data.parent.selectItem(data.item)"
                              :selected="data.selected"
                              :key="JSON.stringify(data.item)"
                              id="img"
                            >
                              <v-avatar>
                                <img :src="`/img/products/${data.item.image}`">
                              </v-avatar>
                            </v-chip>
                          </template>
                          <template slot="item" slot-scope="data">
                            <template v-if="typeof data.item !== 'object'">
                              <v-list-tile-content v-text="data.item"></v-list-tile-content>
                            </template>
                            <template v-else>
                              <v-list-tile-avatar>
                                <img :src="`/img/products/${data.item.image}`">
                              </v-list-tile-avatar>
                            </template>
                          </template>
                        </v-select>
                      </td>
                    </template>
                    <template slot="expand" slot-scope="props">
                      <v-card flat>
                        <v-card-text>
                          <v-layout row wrap>
                            <v-flex xs12 sm12 md4 lg4>
                              <v-layout align-center>
                                <v-checkbox 
                                  color="warning" 
                                  v-model="props.item.state" 
                                  hide-details 
                                  class="shrink mr-2"
                                  v-on:change="changeState(props.item)">
                                >
                                </v-checkbox>
                                <v-text-field 
                                  :disabled="!props.item.state" 
                                  v-model="props.item.price"
                                  @focus ="$event.target.select()"
                                  data-vv-name="price"
                                  v-validate="'required|decimal:2|max:8'"
                                  :error-messages="errors.collect('price')"
                                  label="Precio Producto"
                                  color="warning"
                                  hint="Para poder agregar un precio a este producto, marque la casilla y escriba el monto correspondiente."
                                  persistent-hint
                                ></v-text-field>
                              </v-layout>
                            </v-flex>
                          </v-layout>
                        </v-card-text>
                      </v-card>
                    </template>
                    <template slot="no-data">
                      <center>Sin Datos</center>
                    </template>
                    <template slot="pageText" slot-scope="{ pageStart, pageStop }">
                      From {{ pageStart }} to {{ pageStop }}
                    </template>
                  </v-data-table>
                </v-card>
              </v-flex>
            </v-layout>
          </v-container>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title primary-title>
                    <h3 class="headline mb-0">Datos</h3>
                  </v-card-title>
                  <v-card-text>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md3 lg3>
                        <v-text-field
                          label="Empresa/Cliente"
                          v-model="form.customer"
                          data-vv-name="customer"
                          v-validate="'required|max:64'"
                          :error-messages="errors.collect('customer')"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md3 lg3>
                        <v-text-field
                          label="Persona Contacto"
                          v-model="form.contact"
                          data-vv-name="contact"
                          v-validate="'required|max:64'"
                          :error-messages="errors.collect('contact')"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md3 lg3>
                        <v-text-field
                          label="Teléfono"
                          v-model="form.phone"
                          data-vv-name="phone"
                          v-validate="'required|max:32'"
                          :error-messages="errors.collect('phone')"
                        ></v-text-field>
                      </v-flex>
                      <v-flex xs12 sm12 md3 lg3>
                        <v-text-field
                          label="Dirección"
                          v-model="form.address"
                          data-vv-name="address"
                          v-validate="'required|max:128'"
                          :error-messages="errors.collect('address')"
                        ></v-text-field>
                      </v-flex>
                    </v-layout>
                    <v-layout row wrap>
                      <v-flex xs12 sm12 md3 lg3>
                        <v-text-field
                          label="Plazo de Entrega (Días)"
                          v-model="form.term"
                          data-vv-name="term"
                          v-validate="'required|numeric|max:8'"
                          :error-messages="errors.collect('term')"
                        ></v-text-field>
                      </v-flex>
                    </v-layout>
                  </v-card-text>
                  <v-card-actions>
                    <v-btn :loading="progress" color="warning" @click="generate">GENERAR COTIZACIÓN</v-btn>
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

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    data () {
      return {
        loading: false,
        loader: false,
        search: null,
        progress : false,
        items: [],
        select: [],
        quo: [],
        alerts:[],
        headers: [
          {text: 'Código', value: 'codigo'},
          {text: 'Nombre', value: 'nombre'},
          {text: 'Cantidad', value: 'cantidad'},
          {text: 'Logo Extra', value: 'logo'},
          {text: 'Imagen', value: 'imagen'}
        ],
        form: {
          customer: '',
          contact: '',
          phone:'',
          address:'',
          term: '',
          user_id: null
        },
        dictionary: {
          custom: {
            customer: {
              required: () => 'Este campo es requerido',
              max: 'Este campo debe tener un máximo de 64 caracteres'
            },
            contact: {
              required: () => 'Este campo es requerido',
              max: 'Este campo debe tener un máximo de 64 caracteres'
            },
            phone: {
              required: () => 'Este campo es requerido',
              max: 'Este campo debe tener un máximo de 32 caracteres'
            },
            address: {
              required: () => 'Este campo es requerido',
              max: 'Este campo debe tener un máximo de 128 caracteres'
            },
            term: {
              required: () => 'Este campo es requerido',
              numeric: 'Este campo solo puede contener números enteros',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            image: {
              required: () => 'Este campo es requerido'
            },
            quantity: {
              required: () => 'Este campo es requerido',
              numeric: 'Este campo solo puede contener números enteros',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            extra: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            },
            price: {
              required: () => 'Este campo es requerido',
              decimal: 'El campo debe ser numérico y puede contener 2 decimales',
              max: 'Este campo debe tener un máximo de 8 caracteres'
            }
          }
        }
      }
    },

    computed: {
      ...mapGetters([
        'currentUser',
        'products'
      ])
    },

    watch: {
      search (val) {
        val && this.querySelections(val)
      }
    },

    created() {
      if (this.products.length > 0) {
        let data = this.products.map(({id, code, name, image}) => ({id, code, name, image}))
        let select = this.products.map(({id}) => (id))
        this.items = data
        this.select = select
      }
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      changeState(item) {
        if (!item.state) {
          item.price = 0
        }
      },

      querySelections (v) {
        this.loading = true
        axios.get(`/api/search-product/${v}`)
        .then(response => {
          this.items = response.data.data
          this.loading = false
        })
        .catch(error => {
          this.loading = false
        })
      },
      getDataTable () {
        this.loader = true
        let array = this.select.filter(o => !this.quo.find(x => x.id === o))
        let remove = this.quo.filter(o => !this.select.find(x => x === o.id)).map(i => i.id)
        let post_data = { json_data: JSON.stringify(array) }
        axios.post('/api/select-product', post_data)
        .then((response) => {
          if (response.status === 200) {
            let data = response.data.data
            data.forEach((e) => {
              this.quo.push(e)
            })
            this.loader = false
            this.quo = this.quo.filter(v => !remove.find(x => x === v.id))

            this.quo = this.quo.map((obj) => { 
              let o = Object.assign({}, obj)
              o.state = false
              o.price = 0
              return o
            })
          }
        })
        .catch((error) => {
          this.loader = false
        })
      },
      generate () {
        this.form.user_id = this.currentUser.id
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.alerts = []
            this.progress = true
            let data = this.quo.map(({id, code, name, quantity, extra, url, state, price}) => ({id, code, name, quantity: parseFloat(quantity), extra, url, state, price}))
            let jsonString = {data: JSON.stringify(data), details: JSON.stringify(this.form)};
            axios.post('/api/reporte', jsonString)
            .then((response) => {
              if (response.data.success) {
                this.$store.dispatch('emptyProducts')
                let url = `/${response.data.data}`
                window.open(
                  url,
                  '_blank' 
                )
                this.progress = false
              } else if(!response.data.success){
                this.alerts = response.data.errors
                this.progress = false
              }
            })
            .catch((error) => {
              this.progress = false
            })
          }
        })
      }
    }
  }
</script>

<style scoped>
  #img {
    background: rgba(255,255,255,0.12);
  }
  ul { list-style: none }
</style>
