<template>
  <div class="container" style="text-align:center">
    <h4>Register</h4>
    <form v-if="!isLoggedIn" @submit.prevent="register">
      <label for="firstName">First Name</label>
      <div>
          <input id="firstName" type="text" v-model="firstName" required autofocus>
      </div>

      <label for="lastName">Last Name</label>
      <div>
          <input id="lastName" type="text" v-model="lastName" required autofocus>
      </div>

      <label for="email" >E-Mail Address</label>
      <div>
          <input id="email" type="email" v-model="email" required>
      </div>

      <label for="password">Password</label>
      <div>
          <input id="password" type="password" v-model="password" required>
      </div>

      <label for="password-confirm">Confirm Password</label>
      <div>
          <input id="password-confirm" type="password" v-model="password_confirmation" required>
      </div>

      <div>
          <button type="submit">Register</button>
      </div>
    </form>

    <div v-else>
      <h1> You are already registered, redirecting you to your dashboard shortly... </h1>

    </div>
  </div>
</template>


<script>
  export default {
    data(){
      return {
        firstName: '',
        lastName: '',
        email: '',
        password : '',
        password_confirmation: ''
      }
    },
    methods: {
      register() {
        let data = {
          firstName: this.firstName,
          lastName: this.lastName,
          email: this.email,
          password: this.password,
          password_confirmation:this.password_confirmation
        }


        this.$store.dispatch('register', data)
       .then(() => this.$router.push('/'))
       .catch(err => console.log(err))
      }
    },

    created(){
      if(this.isLoggedIn){
        setTimeout(()=>{ this.$router.push('/dashboard') }, 2000);
      }

    },

    computed : {
      isLoggedIn(){ return this.$store.getters.isLoggedIn }
    },
  }
</script>
