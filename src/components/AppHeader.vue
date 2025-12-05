<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">

      <!-- Brand -->
      <router-link class="navbar-brand" to="/">BookWise</router-link>

      <!-- Primary nav links -->
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item"><router-link class="nav-link" to="/">Home</router-link></li>
        <li class="nav-item"><router-link class="nav-link" to="/books">Book List</router-link></li>
        <li class="nav-item"><router-link class="nav-link" to="/reviews">Reviews</router-link></li>
        <li class="nav-item"><router-link class="nav-link" to="/about">About</router-link></li>
      </ul>

      <!-- Auth controls -->
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
        <!-- Show Login/Register when NOT authenticated -->
        <li v-if="!isAuth" class="nav-item">
          <router-link class="nav-link" to="/login">Login</router-link>
        </li>
        <li v-if="!isAuth" class="nav-item">
          <router-link class="nav-link" to="/register">Register</router-link>
        </li>

        <!-- Show Logout when authenticated -->
        <li v-if="isAuth" class="nav-item">
          <button type="button" class="btn btn-outline-secondary" @click="onLogout">
            Logout
          </button>
        </li>
      </ul>

    </div>
  </nav>
</template>

<script setup>
import { storeToRefs } from 'pinia'
import { useAuthStore }  from '@/stores/auth'
import { useRouter }     from 'vue-router'

const auth   = useAuthStore()
const { isAuthenticated: isAuth } = storeToRefs(auth)
const router = useRouter()

function onLogout() {
  auth.logout().then(() => {
    // after logout, redirect home
    router.push('/')
  })
}
</script>

<style scoped>
.navbar-expand-lg .navbar-toggler { display: none; }
.navbar-nav .btn {
  padding: 0.25rem 0.75rem;
  font-size: 0.9rem;
  margin-left: 0.5rem;
}
</style>
