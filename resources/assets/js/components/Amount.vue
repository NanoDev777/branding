<template>
  <v-card>
    <v-card-text>
      <h3 class="headline mb-0">Precios del producto</h3>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12 lg12>
          <v-layout row wrap>
            <v-flex xs12 sm12 md6 lg5>
              <v-text-field
                label="Cantidad"
                v-model="form.quantity"
                data-vv-name="quantity"
                v-validate="'required|numeric|max:16'"
                :error-messages="errors.collect('quantity')"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md6 lg5>
              <v-text-field
                label="Precio"
                v-model="form.price"
                data-vv-name="price"
                v-validate="'required|decimal:2|max:8'"
                :error-messages="errors.collect('price')"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md2 lg2>
              <v-btn :loading="loading" @click="save">
                Guardar
              </v-btn>
            </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12 lg12>
          <v-data-table
            :headers="headers"
            :items="amounts"
            hide-actions
            class="elevation-1"
          >
            <template slot="items" slot-scope="props">
              <td>
                <v-edit-dialog
                  :return-value.sync="props.item.quantity"
                  lazy
                > {{ props.item.quantity }}
                  <v-text-field
                    slot="input"
                    v-model="props.item.quantity"
                    label="Edit"
                    single-line
                  ></v-text-field>
                </v-edit-dialog>
              </td>
              <td class="text-xs-left">
                <v-edit-dialog
                  :return-value.sync="props.item.price"
                  lazy
                > {{ props.item.price }}
                  <v-text-field
                    slot="input"
                    v-model="props.item.price"
                    label="Edit"
                    single-line
                  ></v-text-field>
                </v-edit-dialog>
              </td>
              <td class="text-xs-left">{{ props.item.created_at | formatDate('DD/MM/YYYY') }}</td>
              <td class="text-xs-left">{{ props.item.updated_at | formatDate('DD/MM/YYYY') }}</td>
              <td>
                <v-btn 
                  :loading="props.item.update" 
                  flat icon color="blue"
                  @click="update(props.item)"
                >
                  <v-icon>edit</v-icon>
                </v-btn>
                <v-btn 
                  :loading="props.item.delete"
                  flat icon color="red"
                  @click="deleted(props.item)"
                >
                  <v-icon>delete</v-icon>
                </v-btn>
              </td>
            </template>
            <template slot="no-data">
              <center>Sin Resultados</center>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'amount',
    props: {
      product: {
        required: true
      }
    },
    data () {
      return {
        form: {
          quantity: null,
          price: null,
          product_id: this.product
        },
        loading: false,
        amounts:[],
        headers: [
          {
            text: 'Cantidad',
            align: 'left',
            sortable: false,
            value: 'cantidad'
          },
          { text: 'Precio', value: 'precio' },
          { text: 'Registrado', value: 'registrado' },
          { text: 'Actualizado', value: 'actualizado' },
          { text: 'Acción', value: 'accion' }
        ],
        dictionary: {
          custom: {
            quantity: {
              required: () => 'Este campo es requerido',
              numeric: 'Este campo solo puede contener números enteros',
              max: 'Este campo debe tener un máximo de 16 caracteres'
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

    created() {
      this.get()
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      get() {
        axios.get('/api/amounts/' + this.product)
        .then(response => {
          let amounts = response.data.data.map((obj) => { 
            let rObj = obj
            rObj['update'] = false
            rObj['delete'] = false
            return rObj
          })
          this.amounts = amounts
        })
      },

      save() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            axios.post('/api/create-amount', this.form)
            .then((response) => {
              if (response.data.success) {
                let amount = response.data.data
                amount["update"] = false
                amount["delete"] = false
                this.amounts.unshift(amount)
                this.$snotify.simple(response.data.message, 'Felicidades')
              }
              this.loading = false
            })
            .catch((error) => {
              this.loading = false
            })
          }
        })
      },

      update(item) {
        item.update = true
        axios.put(`/api/amount/${item.id}`, item)
        .then((response) => {
          if (response.data.success) {
            this.$snotify.simple(response.data.message, 'Felicidades')
          }
          item.update = false
        })
        .catch((error) => {
          item.update = false
        })
      },

      deleted(item) {
        let r = confirm('Realmente desea eliminar el registro?')
        if (r) {
          item.delete = true
          axios.delete('/api/amount/'+item.id)
          .then((response) => {
            if (response.data.success) {
              let index = this.amounts.findIndex(x => x.id == item.id)
              this.amounts.splice(index, 1)
              this.$snotify.simple(response.data.message, 'Felicidades')
            }
            item.delete = false
          })
          .catch((error) => {
            item.delete = false
          })
        }
      }
    }
  }
</script>