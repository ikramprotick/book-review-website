<template>
  <div
    class="home-bg d-flex flex-column"
    :style="{ backgroundImage: `url(${backgroundImg})` }"
    role="main"
  >
    <div class="overlay flex-fill d-flex flex-column justify-content-center">
      <div class="container text-center text-white">
        <h1>Welcome to BookWise</h1>
        <p class="lead">Your community-driven hub for discovering and reviewing books.</p>
        <router-link to="/books" class="btn btn-primary btn-lg mt-3" aria-label="Browse books">
          Browse Books
        </router-link>

        <div class="row g-4 align-items-center mt-5">
          <div class="col-md-6">
            <img
              :src="shelfImg"
              alt="Bookshelf filled with colorful books"
              class="img-fluid rounded shadow-sm"
            />
          </div>
          <div class="col-md-6">
            <img
              :src="readingImg"
              alt="Person reading a book"
              class="img-fluid rounded shadow-sm"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import shelfImg from '@/assets/bookshelf.jpg'
import readingImg from '@/assets/reading.jpg'
import backgroundImg from '@/assets/background.jpg'
</script>

<style scoped>
.home-bg {
  background-size: cover;
  background-position: center;
  min-height: 100vh;
}
.overlay {
  background-color: rgba(0, 0, 0, 0.5);
}
h1, .lead {
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.7);
}
</style>
