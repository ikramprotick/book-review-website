// src/stores/auth.js

import { defineStore } from 'pinia'
import axios from 'axios'

// Always send cookies so PHP session is maintained
axios.defaults.withCredentials = true

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null
  }),

  getters: {
    isAuthenticated: state => !!state.user
  },

  actions: {
    /**
     * Fetch the current user from the session (if logged in)
     */
    async fetchUser() {
      try {
        const { data } = await axios.get('/api/me.php')
        this.user = data.user  // null if not logged in
      } catch {
        this.user = null
      }
    },

    /**
     * Attempt to register a new user.
     * On success the backend must return { user: { id, username } }.
     * Otherwise it should return an error (4xx) with { error: "Message" }.
     */
    async register(username, password) {
      try {
        const res = await axios.post('/api/register.php', { username, password })
        console.log('register response:', res.status, res.data)

        if (res.status === 200 && res.data.user) {
          // automatically log in
          this.user = res.data.user
          return
        }

        // backend returned 200 but no user
        throw new Error(res.data.error || 'Registration failed')
      } catch (err) {
        const serverMsg = err.response?.data?.error
        console.error('Registration error payload:', err.response?.data, err)
        throw new Error(serverMsg || err.message || 'Registration failed')
      }
    },

    /**
     * Attempt to log in with existing credentials.
     * Backend must return { user: { id, username } } on success,
     * or a 4xx + { error: "Message" } on failure.
     */
    async login(username, password) {
      try {
        const res = await axios.post('/api/login.php', { username, password })
        console.log('login response:', res.status, res.data)

        if (res.status === 200 && res.data.user) {
          this.user = res.data.user
        } else {
          throw new Error(res.data.error || 'Invalid credentials')
        }
      } catch (err) {
        const serverMsg = err.response?.data?.error
        console.error('Login failed payload:', err.response?.data, err)
        throw new Error(serverMsg || err.message || 'Login failed')
      }
    },

    /**
     * Log out from the current session.
     * Backend should clear the session and return success.
     */
    async logout() {
      try {
        await axios.post('/api/logout.php')
      } catch (err) {
        console.error('Logout error:', err)
      } finally {
        this.user = null
      }
    }
  }
})
