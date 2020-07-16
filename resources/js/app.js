require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
Vue.use(VueRouter)

import store from './store'
import Axios from 'axios'

Vue.prototype.$http = Axios;
const token = localStorage.getItem('token')
if (token) {
  Vue.prototype.$http.defaults.headers.common['Authorization'] = token
}

window.Vue = require('vue');

// Lazy Load components
const home = Vue.component('home', () => import( /* webpackChunkName: "home" */ './components/home.vue'))
const register = Vue.component('register', () => import( /* webpackChunkName: "register" */ './components/register.vue'))
const dashboard = Vue.component('dashboard', () => import( /* webpackChunkName: "dashboard" */ './components/dashboard.vue'))

const Routes = [
  { path: '/', component: home },
  { path: '/dashboard', component: dashboard, meta: { requireAuth: true } },
  { path: '/register', component: register }
];


const router = new VueRouter({mode: 'history', routes: Routes });

router.beforeEach((to, from, next) => {
  if(to.matched.some(record => record.meta.requireAuth)) {
    if (store.getters.isLoggedIn) {
      next()
      return
    }
    next('/') 
  } else {
    next() 
  }
})

const app = new Vue({
  el: '#app',
  router,
  store
})
