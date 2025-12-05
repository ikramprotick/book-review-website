<template>
  <div class="container my-5 col-md-6 offset-md-3">
    <h2>Login</h2>
    <form @submit.prevent="onLogin" novalidate>
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
      <button type="submit" class="btn btn-primary" :disabled="loading">
        {{ loading ? 'Logging in…' : 'Login' }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'  // Pinia store

const router = useRouter()
const auth   = useAuthStore()

const username = ref('')
const password = ref('')
const loading  = ref(false)

async function onLogin() {
  loading.value = true
  try {
    // Call the Pinia action
    await auth.login(username.value, password.value)
    // Redirect on success
    router.push('/')
  } catch (err) {
    // Customize based on your API’s error shape
    alert('Login failed. Please check your credentials.')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* Center vertically on larger screens */
.container {
  min-height: calc(100vh - 4rem);
  display: flex;
  flex-direction: column;
  justify-content: center;
}
</style>
