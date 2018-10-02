<template>
  <div>
    <vue-dropzone 
      id="drop1"
      ref="dropzone" 
      :options="dropOptions"
      @vdropzone-success ="afterSuccess"
      @vdropzone-error="errorFile"
    ></vue-dropzone>
    <v-btn @click="upload">subir imagenes</v-btn>
  </div>
</template>

<script>
  import vueDropzone from "vue2-dropzone";

  export default {
    props: ['product'],
    data(){
      return {
        dropOptions: {
          url: '/api/create-image',
          headers: { "Authorization": 'Bearer ' + localStorage.getItem('token') },
          params: {product: this.product},
          thumbnailWidth: 200,
          autoProcessQueue: false,
          addRemoveLinks: true,
          dictCancelUpload: 'CANCELAR CARGA',
          dictCancelUploadConfirmation: 'Realmente quiere cancelar la carga de ésta imagen?',
          dictRemoveFile: 'QUITAR IMAGEN',
          dictDefaultMessage: "<i class='material-icons'>backup</i> CARGAR IMAGENES"
        }
      }
    },

    components: {
      vueDropzone
    },

    methods: {
      upload() {
        this.$refs.dropzone.processQueue()
      },

      afterSuccess(file, response) {
        if (response.success) {
          this.$emit('data-received',response.data)
        }
      },

      errorFile(file, message, response) {
        this.$snotify.warning('Uno de los archivos no se cargó correctamente!', 'Alerta', {
          icon: '/img/warning.png'
        })
      }
    }
  }
</script>
