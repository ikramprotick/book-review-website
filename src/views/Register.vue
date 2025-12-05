<template>
  <div class="container my-5 col-md-6 offset-md-3">
    <h2>Register</h2>
    <form @submit.prevent="onRegister" novalidate>
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input
          v-model="username"
          id="username"
          class="form-control"
          required
          aria-required="true"
        />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input
          v-model="password"
          id="password"
          type="password"
          class="form-control"
          required
          aria-required="true"
        />
      </div>
      <button type="submit" class="btn btn-success" :disabled="loading">
        {{ loading ? 'Registering…' : 'Register' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'  // your existing auth.js

const router = useRouter()
const auth   = useAuthStore()

const username = ref('')
const password = ref('')
const loading  = ref(false)

async function onRegister() {
  loading.value = true
  try {
    // calls auth.register(username, password) from your store
    await auth.register(username.value, password.value)
    // on success, navigate to login
    router.push('/login')
  } catch (err) {
    // you can customize error handling based on err.response
    alert('Registration failed. Please try again.')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Optional: center vertically on larger screens */
.container {
  min-height: calc(100vh - 4rem);
  display: flex;
  flex-direction: column;
  justify-content: center;
}
</style>
