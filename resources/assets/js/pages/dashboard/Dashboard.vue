<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
          <v-card flat>
            <v-container fluid>
              <v-layout row child-flex wrap>
                <div>
                  <v-toolbar color="grey lighten-2">
                    <v-toolbar-title>Total Productos</v-toolbar-title>
                    <v-btn icon class="hidden-xs-only">
                      <v-icon>assignment_turned_in</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <span class="headline">{{ products }}</span>
                  </v-toolbar>
                </div>
                <div>
                  <v-toolbar color="yellow lighten-2">
                    <v-toolbar-title>Total Cotizaciones</v-toolbar-title>
                    <v-btn icon class="hidden-xs-only">
                      <v-icon>add_shopping_cart</v-icon>
                    </v-btn>
                    <v-spacer></v-spacer>
                    <span class="headline">{{ quotations }}</span>
                  </v-toolbar>
                </div>
              </v-layout>
            </v-container>
          </v-card>
          <v-container fluid>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title>
                    <span class="headline">Productos Más Cotizados</span> 
                  </v-card-title>
                  <div id="chartdiv" style="width: 100%; height: 400px;"></div>
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
  export default {
    name: 'dashboard',
    data () {
      return {
        success: false,
        products: null,
        quotations: null
      }
    },

    created(){
      this.getCharts()
      this.getTotalProductsQuotaions()
    },

    methods: {
      getCharts() {
        axios.get('/api/max-products')
        .then(response => {
          if (response.data.success) {
            AmCharts.makeChart("chartdiv", {
              "type": "serial",
              "theme": "none",
              "categoryField": "code",
              "rotate": true,
              "startDuration": 1,
              "categoryAxis": {
                "gridPosition": "start",
                "position": "left"
              },
              "trendLines": [],
              "graphs": [
                {
                  "balloonText": "<b>[[name]] - Total Cotizado : [[value]]</b>",
                  "fillAlphas": 10,
                  "id": "AmGraph-1",
                  "lineAlpha": 10,
                  "title": "Income",
                  "type": "column",
                  "valueField": "cotizaciones"
                },
                {
                  "balloonText": "<b>Cantidad Requerida : [[value]]</b>",
                  "fillAlphas": 10,
                  "id": "AmGraph-2",
                  "lineAlpha": 10,
                  "title": "Expenses",
                  "type": "column",
                  "valueField": "total"
                }
              ],
              "guides": [],
              "valueAxes": [
                {
                  "id": "ValueAxis-1",
                  "position": "top",
                  "axisAlpha": 0
                }
              ],
              "allLabels": [],
              "balloon": {},
              "titles": [],
              "dataProvider": response.data.data
            })
            this.success = response.data.success
          }
        })
      },

     getTotalProductsQuotaions() {
        axios.get('/api/total-products')
        .then(response => {
          if (response.data.success) {
            this.products = response.data.data.products
            this.quotations = response.data.data.quotations
          }
        })
      }
    }
  }
</script>
