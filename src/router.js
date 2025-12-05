import { createRouter, createWebHistory } from 'vue-router'

// Page-level components
import Home      from '@/views/Home.vue'
import Login     from '@/views/Login.vue'
import Register  from '@/views/Register.vue'
import BookList  from '@/views/BookList.vue'
import Reviews   from '@/views/News.vue'
import About     from '@/views/About.vue'

const routes = [
  { path: '/',          name: 'Home',     component: Home },
  { path: '/login',     name: 'Login',    component: Login },
  { path: '/register',  name: 'Register', component: Register },
  { path: '/books',     name: 'BookList', component: BookList },
  { path: '/reviews',   name: 'Reviews',  component: Reviews },
  { path: '/about',     name: 'About',    component: About },
  // catch-all redirects back to home
  { path: '/:catchAll(.*)*', redirect: '/' },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
