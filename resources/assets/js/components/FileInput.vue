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
            @change="onImageChange"
          >
        </v-flex>
      </v-layout>
    </v-card-text>
    <v-card-actions>
      <v-spacer></v-spacer>
      <v-btn :loading="loader" @click="upload">subir imagen</v-btn>
    </v-card-actions>
  </v-card>
</template>

<script>
  export default {
    name: 'FileInput',
    props: ['product'],
    data () {
      return {
        loader: false,
        imageName: '',
        imageUrl: '',
        imageFile: ''
      }
    },

    methods: {
      pickFile () {
        this.$refs.image.click ()
      },
      onImageChange(e) {
        let files = e.target.files || e.dataTransfer.files
        if (!files.length)
          return;
        this.createImage(files[0])
        this.imageName = files[0].name
      },
      createImage(file) {
        let reader = new FileReader()
        let vm = this;
        reader.onload = (e) => {
          vm.imageFile = e.target.result
          vm.imageUrl = e.target.result
        };
        reader.readAsDataURL(file)
      },
      upload () {
        this.loader = true
        axios.post('/api/create-image',{image: this.imageFile, id: this.product})
        .then(response => {
          this.$emit('data-received',response.data.data)
          this.loader = false
          this.$snotify.success('Se guardo la imagen correctamente!', 'Felicidades')
        });
      }
    }
  }
</script>