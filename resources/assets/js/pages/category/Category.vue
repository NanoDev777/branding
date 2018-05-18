<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card>
          <v-card-title primary-title>
            <h3 class="headline mb-0">Categorías</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title>
                    <v-btn dark color="grey darken-1" slot="activator" class="mb-2" @click.native="dialog = true">
                      <v-icon dark>note_add</v-icon>
                    </v-btn>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-text-field
                      append-icon="search"
                      label="Buscar"
                      single-line
                      hide-details
                      v-model="search"
                    ></v-text-field>
                  </v-card-title>
                  <v-dialog v-model="dialog" max-width="500px">
                    <v-card>
                      <v-card-title>
                        <span class="headline">{{ formTitle }}</span>
                      </v-card-title>
                      <v-card-text>
                        <v-container grid-list-md>
                          <v-layout wrap>
                            <v-flex xs12 sm12 md12>
                              <v-text-field 
                                label="Nombre" 
                                v-model="editedItem.name"
                                :error-messages="errors.collect('name')"
                                v-validate="'required'"
                                data-vv-name="name"
                                required
                              ></v-text-field>
                            </v-flex>
                            <v-flex xs12 sm12 md12>
                              <v-text-field label="Descripción" v-model="editedItem.description"></v-text-field>
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
                      <v-card-title class="headline">Desea eliminar la categoría?</v-card-title>
                      <v-card-text>
                        Si elimina esta categoria, también se eliminaran todos los productos relacionados a ella.
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
                  <v-data-table
                      :headers="headers"
                      :items="categories"
                      :search="search"
                    >
                      <template slot="items" slot-scope="props">
                        <td>{{ props.item.name }}</td>
                        <td>{{ props.item.description }}</td>
                        <td>
                          <v-btn v-if="permission('categories.update')" icon class="mx-0" @click="editItem(props.item)">
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
  import permission from '../../mixins/permission'

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'category',
    data: () => ({
      dialog: false,
      deleteDialog: false,
      loader: false,
      loading: false,
      search: '',
      headers: [
        { text: 'Nombre', value: 'nombre' },
        { text: 'Descripción', value: 'descripción' },
        { text: 'Acciones', value: 'name', sortable: false }
      ],
      editedIndex: -1,
      editedItem: {
        name: '',
        description: ''
      },
      deleteItem: null,
      defaultItem: {
        name: '',
        description: ''
      },
      dictionary: {
        custom: {
          name: {
            required: () => 'Este campo es requerido'
          }
        }
      }
    }),

    mixins: [permission],

    computed: {
      formTitle () {
        return this.editedIndex === -1 ? 'Registrar Categoría' : 'Editar Categoría'
      },
      ...mapGetters([
        'categories'
      ])
    },

    watch: {
      dialog (val) {
        val || this.close()
      }
    },

    created () {
      this.$store.dispatch('getCategories').then(()=>{
        this.loading = true
      })
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      editItem (item) {
        this.editedIndex = this.categories.indexOf(item)
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
        this.$store.dispatch("deleteCategory", this.deleteItem)
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
              this.updateCategory(this.editedItem)
            } else {
              this.createCategory(this.editedItem)
            }
          }
        })
      },

      updateCategory(category) {
        this.loader = true
        axios.put(`/api/category/${category.id}`, category)
        .then((response) => {
          this.$store.dispatch("updateCategory", response.data.data)
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

      createCategory(category) {
        this.loader = true
        axios.post('/api/create-category', category)
        .then((response) => {
          this.$store.dispatch("addCategory", response.data.data)
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
