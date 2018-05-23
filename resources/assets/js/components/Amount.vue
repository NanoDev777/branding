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
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md6 lg5>
              <v-text-field
                label="Precio"
              ></v-text-field>
            </v-flex>
            <v-flex xs12 sm12 md2 lg2>
              <v-btn>
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
              <td class="text-xs-left">{{ props.item.quantity + 2 }}</td>
              <td>
                <v-btn :loading="props.item.loader" flat icon color="blue" @click="update(props.item)">
                  <v-icon>edit</v-icon>
                </v-btn>
                <v-btn flat icon color="red">
                  <v-icon>delete</v-icon>
                </v-btn>
              </td>
            </template>
          </v-data-table>
        </v-flex>
      </v-layout>
    </v-card-text>
  </v-card>
</template>

<script>
  export default {
    name: 'amount',
    props: {
      amounts: {
        required: true,
        type: Array
      }
    },

    data () {
      return {
        headers: [
          {
            text: 'Cantidad',
            align: 'left',
            sortable: false,
            value: 'cantidad'
          },
          { text: 'Precio', value: 'precio' },
          { text: 'Precio Bs.', value: 'presiob' },
          { text: 'Acción', value: 'accion' }
        ]
      }
    },

    methods: {
      save() {

      },

      update(item) {
        console.log(item)
        item.loader = true
        axios.put(`/api/amount/${item.id}`, item)
        .then((response) => {
          item.loader = false
          this.$snotify.simple(response.data.message, 'Felicidades')
        })
        .catch((error) => {
          item.loader = false
        })
      }
    }
  }
</script>