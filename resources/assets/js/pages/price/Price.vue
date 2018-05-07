<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-progress-circular indeterminate :size="70" :width="7" color="grey darken-1" v-if="!loading"></v-progress-circular>
        <v-card v-show="loading">
          <v-card-title primary-title>
            <h3 class="headline mb-0">Precios</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <div>
                    <v-dialog v-model="dialog" max-width="500px">
                      <v-btn dark color="grey darken-1" slot="activator" class="mb-2">Registrar Nuevo</v-btn>
                      <v-card>
                        <v-card-title>
                          <span class="headline">{{ formTitle }}</span>
                        </v-card-title>
                        <v-card-text>
                          <v-container grid-list-md>
                            <v-layout wrap>
                              <v-flex xs12 sm12 md12>
                                <v-text-field 
                                  label="Cantidad" 
                                  v-model="editedItem.quantity"
                                  :error-messages="errors.collect('quantity')"
                                  v-validate="'required'"
                                  data-vv-name="quantity"
                                  required
                                ></v-text-field>
                              </v-flex>
                              <v-flex xs12 sm12 md12>
                                <v-text-field 
                                  label="Logo" 
                                  v-model="editedItem.logo"
                                  :error-messages="errors.collect('logo')"
                                  v-validate="'required'"
                                  data-vv-name="logo"
                                  required
                                ></v-text-field>
                              </v-flex>
                              <v-flex xs12 sm12 md12>
                                <v-text-field 
                                  label="Utilidad" 
                                  v-model="editedItem.utility"
                                  :error-messages="errors.collect('utility')"
                                  v-validate="'required'"
                                  data-vv-name="utility"
                                  required
                                ></v-text-field>
                              </v-flex>
                            </v-layout>
                          </v-container>
                        </v-card-text>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn :disabled="loader" color="blue darken-1" flat @click.native="close">Cancelar</v-btn>
                          <v-btn :loading="loader" color="blue darken-1" flat @click.native="save">Aceptar</v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                    <v-dialog v-model="deleteDialog" max-width="290">
                      <v-card>
                        <v-card-title class="headline">Desea eliminar el precio?</v-card-title>
                        <v-card-text>
                          Realmente desea eliminar este registro.
                        </v-card-text>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn :disabled="loader" color="green darken-1" flat="flat" @click.native="deleteDialog = false">Cancelar                
                          </v-btn>
                          <v-btn :loading="loader" color="green darken-1" flat="flat" @click.native="destroyItem()">  Aceptar
                          </v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                    <v-card-title>
                      <v-spacer></v-spacer>
                      <v-text-field
                        append-icon="search"
                        label="Buscar"
                        single-line
                        hide-details
                        v-model="search"
                      ></v-text-field>
                    </v-card-title>
                    <v-data-table
                      :headers="headers"
                      :items="prices"
                      :search="search"
                    >
                      <template slot="items" slot-scope="props">
                        <td>{{ props.item.quantity }}</td>
                        <td>{{ props.item.logo }}</td>
                        <td>{{ props.item.utility }}</td>
                        <td>
                          <v-btn icon class="mx-0" @click="editItem(props.item)">
                            <v-icon color="teal">edit</v-icon>
                          </v-btn>
                          <v-btn icon class="mx-0" @click="showDeleteDialog(props.item)">
                            <v-icon color="pink">delete</v-icon>
                          </v-btn>
                        </td>
                      </template>
                      <template slot="no-data">
                        <v-alert :value="true" color="error" icon="warning">
                          Lo sentimos, no se encontraron resultados.
                        </v-alert>
                      </template>
                    </v-data-table>
                  </div>
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
    name: 'price',
    data: () => ({
      dialog: false,
      deleteDialog: false,
      loader: false,
      loading: false,
      search: '',
      headers: [
        { text: 'Cantidad', value: 'cantidad' },
        { text: 'Logo', value: 'logo' },
        { text: 'Utilidad', value: 'utilidad' },
        { text: 'Acciones', value: 'acciones', sortable: false }
      ],
      editedIndex: -1,
      editedItem: {
        quantity: null,
        logo: null,
        utility: null
      },
      deleteItem: null,
      defaultItem: {
        quantity: null,
        logo: null,
        utility: null
      },
      dictionary: {
        custom: {
          quantity: {
            required: () => 'Este campo es requerido'
          },
          logo: {
            required: () => 'Este campo es requerido'
          },
          utility: {
            required: () => 'Este campo es requerido'
          },
        }
      }
    }),

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Registrar Precio' : 'Editar Precio'
      },
      ...mapGetters([
        'prices'
      ])
    },

    watch: {
      dialog (val) {
        val || this.close()
      }
    },

    created () {
      this.$store.dispatch("getPrices").then(()=>{
        this.loading = true
      })
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      editItem (item) {
        this.editedIndex = this.prices.indexOf(item)
        this.editedItem = Object.assign({}, item)
        this.dialog = true
      },

      showDeleteDialog (item) {
        this.deleteDialog = true
        this.deleteItem = item
        console.log(this.deleteItem)
      },

      destroyItem () {
        this.loader = true
        this.$store.dispatch("deletePrice", this.deleteItem)
        .then((response)=>{
          this.loader = false
          this.deleteDialog = false
          this.$snotify.success(response.data.response, 'Felicidades')
        })
      },

      close () {
        this.dialog = false
        setTimeout(() => {
          this.editedItem = Object.assign({}, this.defaultItem)
          this.editedIndex = -1
        }, 300)
      },

      save () {
        this.$validator.validateAll().then((result) => {
          if (result) {
            if (this.editedIndex > -1) {
              this.updatePrice(this.editedItem)
            } else {
              this.createPrice(this.editedItem)
            }
          }
        })
      },

      updatePrice(price) {
        this.loader = true
        axios.put(`/api/price/${price.id}`, price)
        .then((response) => {
          this.$store.dispatch("updatePrice", response.data.data)
          .then(()=>{
            this.loader = false
            this.dialog = false
            this.$snotify.success('Editado correctamente!', 'Felicidades')
          })
        })
        .catch((error) => {
          this.loader = false
          this.dialog = false
          this.$snotify.error(error.response.data.msg, 'Error')
        })
      },

      createPrice(price) {
        this.loader = true
        axios.post('/api/create-price', price)
        .then((response) => {
          this.$store.dispatch("addPrice", response.data.data)
          .then(()=>{
            this.loader = false
            this.dialog = false
            this.$snotify.success('Registrado correctamente!', 'Felicidades')
          })
        })
        .catch((error) => {
          this.loader = false
          this.dialog = false
          this.$snotify.error(error.response.data.msg, 'Error')
        })
      }
    }
  }
</script>
