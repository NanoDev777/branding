<template>
  <v-container fluid grid-list-md>
    <v-layout>
      <v-flex d-flex xs12 sm12 md12>
        <v-card v-show="success">
          <v-card-title primary-title>
            <h3 class="headline mb-0">{{ addSubtitle }}</h3>
          </v-card-title>
          <v-container fluid>
            <v-layout row wrap>
              <v-flex xs12 sm12 md5 lg5>
                <v-layout row wrap>
                  <v-flex xs12 sm12 md12 lg12>
                    <v-text-field
                      label="Descripcion"
                      v-model="profile.description"
                      data-vv-name="description"
                      v-validate="'required'"
                      :error-messages="errors.collect('description')"
                    ></v-text-field>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
            <v-layout>
              <v-flex xs12 sm12 md12 lg12>
                <v-card>
                  <v-card-title>
                    <h3>Permisos De Acceso</h3>
                    <v-spacer></v-spacer>
                    <v-icon x-large>https</v-icon>
                  </v-card-title>
                  <v-card-text>
                    <v-layout row wrap>
                      <v-flex xs12 sm6 md3 lg3 v-for="(label, id, index) in actions" :key="id">
                        <v-checkbox
                          color="warning"
                          hide-details
                          :label="String(label)" 
                          :id="id" 
                          :index="index" 
                          :value="id"
                          v-model="profile.action_list"
                        ></v-checkbox>
                      </v-flex>
                    </v-layout>
                    <small>Por favor, seleccione uno o más permisos a los que tendrá acceso este perfil.</small>
                  </v-card-text>
                  <v-divider class="mt-5"></v-divider>
                  <v-card-actions>
                    <v-btn :disabled="loading" to="/profiles">
                      Cancelar
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn 
                      @click="save" 
                      v-if="profile.description && profile.action_list.length > 0" 
                      :loading="loading"
                    >
                      Aceptar
                    </v-btn>
                  </v-card-actions>
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
  import ActionService from '../../class/action/ActionService'
  import Profile from '../../class/profile/Profile'

  export default {
    $_veeValidate: {
      validator: 'new'
    },
    name: 'form-profile',
    data () {
      return {
        success: false,
        loading: false,
        profile: new Profile(),
        actions: [],
        id: this.$route.params.id,
        success: false,
        dictionary: {
          custom: {
            description: {
              required: () => 'Este campo es requerido'
            }
          }
        }
      }
    },

    computed: {
      addSubtitle () {
        if(this.id) {
          return 'Editar Perfil'
        }
        return 'Registrar Nuevo Perfil'
      }
    },

    created(){
      this.actions = new ActionService(axios.get('/api/actions'))
      this.actions.list()
      .then(response => {
        this.actions = response.list
      })  

      if (this.id) {
        this.showPerfil()
      }else{
        this.success = true
      }
    },

    mounted () {
      this.$validator.localize('en', this.dictionary)
    },

    methods: {
      showPerfil() {
        axios.get(`/api/profile/${this.id}`)
        .then(response => {
          if (response.data.success) {
            this.profile = response.data.profile
            this.success = true
          }
        })
      },

      save() {
        this.$validator.validateAll().then((result) => {
          if (result) {
            this.loading = true
            if(this.id) {
              this._save = axios.put(`/api/profile/${this.id}`, this.profile)
            } else {
              this._save = axios.post('/api/create-profile', this.profile)
            }
            this._save
            .then(response => {
              if(response.data.success) {
                this.$router.push('/profiles')
                this.$snotify.simple(response.data.message, 'Felicidades')
              }
              this.loading = false
            })
            .catch(error =>{
              this.loading = false
            })
          }
        })
      }
    }

  }
</script>

