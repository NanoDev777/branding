<template>
  <v-card>
    <v-card-text>
      <h3 class="headline mb-0">Cargar Imagenes</h3>
      <v-layout row wrap>
        <v-flex xs12 sm12 md12 lg12>
          <img :src="imageUrl" height="150" v-if="imageUrl"/>
          <v-text-field 
            label="Seleccione una imagen" 
            @click='pickFile' 
            v-model='imageName'
            prepend-icon='attach_file'>
          </v-text-field>
          <input
            type="file"
            style="display: none"
            ref="image"
            accept="image/*"
            @change="onFilePicked"
          >
        </v-flex>
      </v-layout>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn>subir imagen</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: 'FileInput',
    data () {
      return {
        imageName: '',
        imageUrl: '',
        imageFile: ''
      }
    },

    methods: {
      pickFile () {
        this.$refs.image.click ()
      },
      onFilePicked (e) {
        const files = e.target.files
        if(files[0] !== undefined) {
          this.imageName = files[0].name
        if(this.imageName.lastIndexOf('.') <= 0) {
          return
        }
        const fr = new FileReader ()
        fr.readAsDataURL(files[0])
        fr.addEventListener('load', () => {
          this.imageUrl = fr.result
          this.imageFile = files[0] // this is an image file that can be sent to server...
        })
        } else {
          this.imageName = ''
          this.imageFile = ''
          this.imageUrl = ''
        }
      }
    }
  }
</script>