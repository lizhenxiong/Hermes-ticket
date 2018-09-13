import App from './components/App.vue';
import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './route/routes.js';

Vue.use(VueRouter);

const router = new VueRouter({
  routes
})

new Vue({
  router,
  render: h => h(App)
}).$mount('#app')