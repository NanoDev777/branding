<v-dialog v-model="dialog" persistent max-width="290">
            <v-card>
              <v-card-title class="headline">Eliminar Producto</v-card-title>
              <v-card-text>Realmente desea eliminar los datos de este producto?</v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click.native="dialog = false">Cancelar</v-btn>
                <v-btn color="blue darken-1" flat @click="deleteProduct">Aceptar</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>

deleteProduct() {
        axios.delete('/api/product/'+this.id)
        .then((response) => {
          console.log(response)
          this.dialog = false
          this.$router.push({ name: 'Products' })
          this.$snotify.success(response.data.data, 'Felicidades')
        })
        .catch((error) => {
          console.log(error)
        })
      },