<template>
  <div class="container">
    <h2 style="padding-bottom:10%;padding-top:10%">Login Page</h2>
    <form v-if="!isLoggedIn" @submit.prevent="handleSubmit">
      <div class="form-group">
        <label for="email">E-mail</label>
        <input type="text" v-model="email" name="email" class="form-control" :class="{ 'is-invalid': submitted && !email }" />
        <div v-show="submitted && !email" class="invalid-feedback">email is required</div>
      </div>
      <div class="form-group">
        <label htmlFor="password">Password</label>
        <input type="password" v-model="password" name="password" class="form-control" :class="{ 'is-invalid': submitted && !password }" />
        <div v-show="submitted && !password" class="invalid-feedback">Password is required</div>
      </div>
      <div class="form-group">
        <button class="btn btn-primary">Login</button>
        <router-link to="/register" class="btn btn-link">Register</router-link>
      </div>
      <p style="color:red" v-if="error">{{this.error}}</p>
    </form>
    <div v-else>
      <p> You are already logged in, redirecting you to dashboard shortly...</p>
    </div>
  </div>
</template>


<script>
export default {
  data () {
    return {
      email: '',
      password: '',
      submitted: false,
      error: ''
    }
  },

  methods: { 
    handleSubmit(){
      this.submitted = true;
      const { email, password } = this;
      if (email && password) {
        this.$store.dispatch('login', { email, password }).then(() => this.$router.push('/dashboard')).catch(err => this.error = err.response.data.error)
      }
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
