<template>
  <v-layout row justify-center>
    <v-dialog
      v-model="dialog"
      persistent
      width="800"
    >
      <v-card>
        <v-card-title
          class="headline grey lighten-2"
          primary-title
        >
          Productos Seleccionados
          <v-spacer></v-spacer>
          <v-btn small @click="emptyProducts()">Borrar Todo <v-icon small>delete_forever</v-icon></v-btn>
        </v-card-title>
        <v-card-text>
          <v-data-table
            :headers="headers"
            :items="products"
            class="elevation-1"
          >
            <template slot="items" slot-scope="props">
              <td>
                <v-avatar
                  tile="tile"
                  size="36px"
                  color="grey lighten-4"
                >
                  <img :src="'/img/products/'+props.item.image" alt="avatar">
                </v-avatar>
              </td>
              <td class="justify-center">{{ props.item.code }}</td>
              <td class="justify-center">{{ props.item.name }}</td>
              <td class="justify-center">{{ props.item.category }}</td>
              <td class="justify-center layout px-0">
                <v-btn flat icon @click="deleteItem(props.item.id)">
                  <v-icon small>delete</v-icon>
                </v-btn>
              </td>
            </template>
            <template slot="pageText" slot-scope="props">
              Filas {{ props.pageStart }} - {{ props.pageStop }} de {{ props.itemsLength }}
            </template>
            <template slot="no-data">
              <center>Ningún producto elegido</center>
            </template>
          </v-data-table>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions>
          <v-btn
            color="primary"
            flat
            @click.native="hide"
          >
            Cerrar
          </v-btn>
          <v-spacer></v-spacer>
          <v-btn
            color="primary"
            flat
            :to="{ name: 'CreateQuotation'}"
          >
            Cotizar
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-layout>
</template>

<script>
  import { mapGetters } from 'vuex'

  export default {
    data() {
      return {
        search: '',
        pagination: {},
        selected: [],
        headers: [
          {
            text: 'Foto',
            align: 'left',
            sortable: false,
            value: 'foto'
          },
          { text: 'Código', value: 'codigo' },
          { text: 'Nombre', value: 'nombre' },
          { text: 'Categoría', value: 'categoria' },
          { text: 'Opciones', value: 'foto', sortable: false }
        ],
        desserts: [
          {
            value: false,
            name: 'Frozen Yogurt',
            calories: 159,
            fat: 6.0,
            carbs: 24,
            protein: 4.0,
            iron: '1%'
          },
          {
            value: false,
            name: 'Ice cream sandwich',
            calories: 237,
            fat: 9.0,
            carbs: 37,
            protein: 4.3,
            iron: '1%'
          },
          {
            value: false,
            name: 'Eclair',
            calories: 262,
            fat: 16.0,
            carbs: 23,
            protein: 6.0,
            iron: '7%'
          },
          {
            value: false,
            name: 'Cupcake',
            calories: 305,
            fat: 3.7,
            carbs: 67,
            protein: 4.3,
            iron: '8%'
          },
          {
            value: false,
            name: 'Gingerbread',
            calories: 356,
            fat: 16.0,
            carbs: 49,
            protein: 3.9,
            iron: '16%'
          },
          {
            value: false,
            name: 'Jelly bean',
            calories: 375,
            fat: 0.0,
            carbs: 94,
            protein: 0.0,
            iron: '0%'
          },
          {
            value: false,
            name: 'Lollipop',
            calories: 392,
            fat: 0.2,
            carbs: 98,
            protein: 0,
            iron: '2%'
          },
          {
            value: false,
            name: 'Honeycomb',
            calories: 408,
            fat: 3.2,
            carbs: 87,
            protein: 6.5,
            iron: '45%'
          },
          {
            value: false,
            name: 'Donut',
            calories: 452,
            fat: 25.0,
            carbs: 51,
            protein: 4.9,
            iron: '22%'
          },
          {
            value: false,
            name: 'KitKat',
            calories: 518,
            fat: 26.0,
            carbs: 65,
            protein: 7,
            iron: '6%'
          }
        ]
      }
    },

    props: {
      dialog: {
        type: Boolean
      },
      loader: {
        type: Boolean
      }
    },

    computed: {
      ...mapGetters([
        'products',
      ])
    },

    methods: {
      deleteItem(item) {
        this.$store.dispatch('deleteProduct', item)
      },

      hide () {
        this.$emit('hide')      
      },

      emptyProducts() {
        this.$store.dispatch('emptyProducts')
      }
    }
  }
</script>
