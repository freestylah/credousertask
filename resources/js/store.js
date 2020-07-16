import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    status: '',
    token: localStorage.getItem('token') || '',
    user: {}
  },

  mutations: {
    auth_request(state){
      state.status = 'loading'
    },

    auth_success(state, token, user){
      state.status = 'success'
      state.token = token
      state.user = user
    },

    auth_error(state){
      state.status = 'error'
    },

    logout(state){
      state.status = ''
      state.token = ''
    },
  },

  actions: {
    login({commit}, user) {

      return new Promise((resolve, reject) => {
        commit('auth_request')
        axios({url: 'http://localhost:8000/api/login', data: user, method: 'POST' })
          .then(resp => {
            const token = resp.data.token
            const user = resp.data.user
            localStorage.setItem('token', token)
            axios.defaults.headers.common['Authorization'] = token
            commit('auth_success', token, user)
            resolve(resp)
          })
          .catch(err => {

            if(err.response.data.error) {
              console.log(err.response.data.error)
            }

            commit('auth_error')
            localStorage.removeItem('token')
            reject(err)
          })
      })
    },

    register({commit}, user){
      return new Promise((resolve, reject) => {
        axios({url: 'http://localhost:8000/api/register', data: user, method: 'POST' })
          .then(resp => {
            const token = resp.data.token
            const user = resp.data.user
            localStorage.setItem('token', token)
            axios.defaults.headers.common['Authorization'] = token
            commit('auth_success', token, user)
            resolve(resp)
          })
          .catch(err => {
            console.log(err.response)
            commit('auth_error', err)
            localStorage.removeItem('token')
            reject(err)
          })
      })
    },

    getUserInfo({commit,state}){
      return new Promise((resolve, reject) => {
        //console.log(state.token)
        commit('auth_request')

        axios({url: `http://localhost:8000/api/user`, headers: { 'Authorization': `Bearer ${state.token}` }, method: 'GET' })
          .then(res => {
            state.user = res.data
            resolve(res)
          })
          .catch(err => {
            console.log(err.response)
            commit('auth_error', err)
            localStorage.removeItem('token')
            reject(err)
          })
      })
    },

    update({commit,state}, user){
      return new Promise((resolve, reject) => {
        axios({url: `http://localhost:8000/api/users/edit/${user.id}`, headers: { 'Authorization': `Bearer ${state.token}` }, data: user, method: 'PUT' })
          .then(res => {
            state.user = res.data
            resolve(res)
          })
          .catch(err => {
            console.log(err.response)
            commit('auth_error', err)
            localStorage.removeItem('token')
            reject(err)
          })
      })
    },


    // Delete user's token both locally & from db
    logout({commit,state}){
      return new Promise((resolve, reject) => {
        // delete user token both locally and from our sqlite db
        axios({url: 'http://localhost:8000/api/logout', headers: { 'Authorization': `Bearer ${state.token}` }, method: 'POST' })
          .then(() => {
            commit('logout')
            state.token = ''
            localStorage.removeItem('token')
            delete axios.defaults.headers.common['Authorization']
            resolve()
          })
          .catch(err => {
            console.log(err.response)
            commit('auth_error', err)
            localStorage.removeItem('token')
            reject(err)
          })
      })
    },


    // When the user is deleted, and the user send invalid token, detect that and send him to login
    logout_expired({commit}){
      return new Promise((resolve, reject) => {
        commit('logout')
        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
        resolve()
      })
    }

  },

  getters : {
    isLoggedIn: state => !!state.token,
    authStatus: state => state.status,
  }

})
