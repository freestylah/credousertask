<template>
  <div class="container" style="text-align:center">

    <h1> Your details </h1>

    <form @submit.prevent="update">
      <label for="firstName">First Name</label>
      <div>
        <input id="firstName" type="text" v-model="firstName" required>
      </div>

      <label for="lastName">Last Name</label>
      <div>
        <input id="lastName" type="text" v-model="lastName" required>
      </div>

      <label for="email" >E-Mail Address</label>
      <div>
        <input id="email" type="email" v-model="email" disabled>
      </div>

      <label for="oldpassword">Old Password</label>
      <div>
        <input id="oldpassword" type="password" v-model="oldpassword">
      </div>

      <label for="password">New Password</label>
      <div>
        <input id="password" type="password" v-model="password">
      </div>

      <label for="password-confirm">Confirm New Password</label>
      <div>
        <input id="password-confirm" type="password" v-model="password_confirmation">
      </div>

      <label for="createdat" > Created at:</label>
      <div>
        <input id="createdat" type="createdat" v-model="createdat" disabled>
      </div>

      <div>
        <button type="submit">Update</button>
      </div>

      <div style="padding-top:15%">
        <button @click="logout">Log out</button>
      </div>

    </form>



  </div>
</template>


<script>
export default {
  data(){
    return {
      id: '',
      firstName: '',
      lastName: '',
      email: '',
      oldpassword:'',
      password : '',
      password_confirmation: '',
      createdat: ''
    }
  },
  methods: {
    update() {
      let data = {
        id: this.id,
        firstName: this.firstName,
        lastName: this.lastName,
        email: this.email,
        oldpassword: this.oldpassword,
        password: this.password,
        password_confirmation:this.password_confirmation
      }

      this.$store.dispatch('update', data)
        .then(() => { 
          console.log("Updated")
        })
        .catch(err => console.log(err))
    },

    logout() {
      this.$store.dispatch('logout')
        .then(() => {
          this.$router.push('/')
        })
    },

    getUserData(){
      this.$store.dispatch('getUserInfo')
        .then(res => {

          console.log(res.data)
          this.id = res.data.id
          this.firstName = res.data.firstName
          this.lastName = res.data.lastName
          this.email = res.data.email
          this.createdat = res.data.created_at
        })
        .catch(err => console.log(err))
    },

    checkExpired(){
      this.$http.interceptors.response.use(undefined, function (err) {
      return new Promise(function (resolve, reject) {
        if (err.status === 401 && err.config && !err.config.__isRetryRequest) {
          this.$store.dispatch(logout_expired)
        }
        throw err;
      });
    });
    }
  },

  created() {
    this.checkExpired()
    this.getUserData()
  },

}

</script>
