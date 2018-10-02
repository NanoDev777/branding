<template>
  <div id="app">
    <v-app light>
      <vue-snotify/>
      <router-view></router-view>
      <feedback-message></feedback-message>
    </v-app>
  </div>
</template>

<script>
  import FeedbackMessage from './components/FeedbackMessage.vue'
  export default {
    name: 'App',
    components: {
      'feedback-message': FeedbackMessage
    }
  } 
</script>
