<template>
  <v-container fluid grid-list-md>
    <v-layout row wrap>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
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
                        <v-dialog v-model="dialog" width="538px">
                          <v-card>
                            <img v-if="url" class="img-responsive" :src="'/img/products/'+url" />
                          </v-card>
                        </v-dialog>
                        <v-carousel>
                          <v-carousel-item
                            v-for="(item,i) in images"
                            :key="i"
                            :src="'/img/products/'+item.image"
                            >
                            <div title="Eliminar Imagen">
                              <v-btn fab flat small color="red accent-3" @click="deleteImg(item.id)" :loading="imgloader">
                                <v-icon>delete</v-icon>
                              </v-btn>
                            </div>
                            <v-btn <v-btn fab flat small color="grey darken-2" @click="open(item.image)">
                              <v-icon>zoom_in</v-icon>
                            </v-btn>
                          </v-carousel-item>
                        </v-carousel>
                      </v-card>
                      <br><hr>
                      <v-card>
                        <b>CÓDIGO: {{ product.code }}</b>
                      </v-card>
                      <pre>{{ $data }}</pre>
                    </v-flex>
                  <v-spacer></v-spacer>
                  <v-flex xs12 sm12 md6 lg6>
                    <v-card>
                      <v-card-title primary-title>
                        <v-layout row wrap>
                          <v-flex xs12 sm12 md12 lg12>
                            <h3 class="headline mb-0">{{ product.name }}</h3>
                            <div>{{ product.description }}</div>
                            <br>
                            <div><span class="grey--text">Especificaciones Artículo</span></div>
                            <div><span>{{ product.height }} / {{ product.width }} / {{ product.thickness }} cm | {{ product.weight }} gr.</span></div>
                            <br>
                            <div><span class="grey--text">Especificaciones Embalaje</span></div>
                            <div v-if="packing !== null">
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
                                  <template slot="no-data">
                                    <center>Sin Resultados</center>
                                  </template>
                                </v-data-table>
                              </v-flex>
                            </v-layout>
                          </v-flex>
                        </v-layout>
                      </v-card-title>
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
                      <amount :product="id"></amount>
                    </v-flex>
                  </v-layout>
                </v-container>
              </v-card>
              <v-card flat>
                <v-container fluid>
                  <v-layout>
                    <v-flex xs12 sm12 md12 lg12>
                      <packing :product="id"></packing>
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
  import { mapGetters } from 'vuex'
  import Product from '../../class/product/Product'
  import FileInput from '../../components/FileInput.vue'
  import Amount from '../../components/Amount.vue'
  import Packing from '../../components/Packing.vue'

  export default {
    name: 'show-product',
    props: ["id"],
    components: {
      'file-input' : FileInput,
      'amount' : Amount,
      'packing' : Packing
    },
    data () {
      return {
        dialog: false,
        url: '',
        active: null,
        success: false,
        imgloader: false,
        product: new Product(),
        colors: [],
        images: [],
        headerColor: [
          {
            text: 'Color',
            align: 'left',
            sortable: false,
            value: 'color'
          },
          { text: 'Talla', value: 'talla', align: 'left' }
        ]
      }
    },

    computed: {
      ...mapGetters([
        'packing'
      ])
    },

    created() {
      this.showProduct()
    },

    methods: {
      open(url) {
        this.dialog = true
        this.url = url
      },

      deleteImg(id) {
        let r = confirm('Realmente desea eliminar esta imagen?')
        if (r) {
          this.imgloader = true
          axios.delete('/api/image/'+id)
          .then((response) => {
            if (response.data.success) {
              let index = this.images.findIndex(x => x.id === id)
              this.images.splice(index, 1)
              this.$snotify.simple(response.data.message, 'Felicidades')
            }
            this.imgloader = false
          })
          .catch(error => {
            this.imgloader = false
          })
        }
      },

      showProduct() {
        axios.get('/api/product/' + this.id)
        .then(response => {
          if (response.data.success) {
            this.product = response.data.data
            this.images = response.data.data.images.map(({id, image}) => ({id, image}))
            let amounts = response.data.data.amounts.map((obj) => { 
              let rObj = obj
              rObj['update'] = false
              rObj['delete'] = false
              return rObj
            })
            this.$store.dispatch('getAmounts', amounts)
            this.$store.dispatch('getPacking', response.data.data.packing)
            let colors = response.data.data.colors
            this.colors = colors.map((obj) => { 
              let rObj = {}
              rObj['name'] = obj.name
              rObj['size'] = obj.pivot['size']
              return rObj
            })
            this.success = response.data.success
          }
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
<style scoped>
  table.table tbody td, table.table tbody th {
    height: 30px;
  }
  .img-responsive {
    max-width: 100%;
    height: auto;
    display:block;
  }
</style>