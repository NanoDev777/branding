<template>
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
</template>
<script>
  export default {
    name: 'graphic',
    
    created(){
      this.getCharts()
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
          }
        })
      }
    }
  }
</script>