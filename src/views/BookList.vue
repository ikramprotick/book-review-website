<template>
  <section class="container my-5" aria-labelledby="books-heading">
    <h2 id="books-heading">Book Collection</h2>

    <!-- Search Controls -->
    <div class="row mb-4 g-3">
      <div class="col-md-3">
        <label for="fieldSelect" class="form-label">Search by</label>
        <select id="fieldSelect" v-model="selectedField" class="form-select">
          <option v-for="f in fields" :key="f" :value="f">
            {{ fieldLabels[f] }}
          </option>
        </select>
      </div>
      <div class="col-md-6">
        <input
          id="searchInput"
          v-model="searchQuery"
          type="text"
          class="form-control"
          :placeholder="`Search by ${fieldLabels[selectedField]}`"
        />
      </div>
    </div>

    <!-- Book Cards -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
      <div v-for="book in filteredBooks" :key="book.id" class="col">
        <div class="card h-100 shadow-sm d-flex flex-column">
          <img
            :src="book.cover"
            class="card-img-top"
            :alt="book.title"
            @click="showDetails(book)"
            style="cursor: pointer;"
          />
          <div
            class="card-body flex-fill"
            @click="showDetails(book)"
            style="cursor: pointer;"
          >
            <h5 class="card-title">{{ book.title }}</h5>
            <p class="card-text mb-1">
              <strong>Author:</strong> {{ book.author }}
            </p>
            <p class="card-text mb-3">
              <strong>Genre:</strong> {{ book.genre }}
            </p>
          </div>
          <div class="card-footer bg-transparent border-0 mt-auto">
            <button
              v-if="isAuth"
              @click.stop="addToCart(book)"
              class="btn btn-primary w-100"
            >
              Add to Cart
            </button>
            <router-link
              v-else
              to="/login"
              class="btn btn-outline-secondary w-100"
            >
              Login to Add
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <p v-if="filteredBooks.length === 0" class="text-center mt-4">
      No books match your search.
    </p>

    <!-- Detail Modal -->
    <BookDetailModal
      v-if="selectedBook"
      :book="selectedBook"
      @close="selectedBook = null"
    />
  </section>
</template>

<script setup>
import { ref, computed } from 'vue'
import booksData from '@/data/books.json'
import { useAuthStore } from '@/stores/auth'
import { storeToRefs } from 'pinia'
import { useCartStore } from '@/stores/cart'
import BookDetailModal from '@/components/BookDetailModal.vue'

// Auth
const auth = useAuthStore()
const { isAuthenticated: isAuth } = storeToRefs(auth)

// Cart
const cart = useCartStore()
function addToCart(book) {
  cart.addToCart(book)
}

// Search logic
const searchQuery = ref('')
const selectedField = ref('title')
const fields = ['title', 'genre', 'author']
const fieldLabels = { title: 'Title', genre: 'Genre', author: 'Author' }

const filteredBooks = computed(() => {
  const q = searchQuery.value.trim().toLowerCase()
  return booksData.filter((b) => {
    const val = String(b[selectedField.value]).toLowerCase()
    return val.includes(q)
  })
})

// Modal state
const selectedBook = ref(null)
function showDetails(book) {
  selectedBook.value = book
}
</script>

<style scoped>
.card {
  transition: transform 0.2s;
}
.card:hover {
  transform: translateY(-5px);
}
</style>
