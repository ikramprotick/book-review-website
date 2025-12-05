<template>
  <div class="container my-5">
    <h2 class="mb-4">Latest Reviews</h2>

    <!-- Search & Filter Controls -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <input
          v-model="searchQuery"
          type="text"
          class="form-control"
          placeholder="Search reviews"
          aria-label="Search reviews"
        />
      </div>
      <div class="col-md-2">
        <select v-model="selectedGenre" class="form-select" aria-label="Filter by genre">
          <option value="All">All Genres</option>
          <option v-for="g in genres" :key="g" :value="g">{{ g }}</option>
        </select>
      </div>
      <div class="col-md-2">
        <select v-model="selectedRating" class="form-select" aria-label="Filter by rating">
          <option value="All">All Ratings</option>
          <option v-for="r in ratings" :key="r" :value="r">{{ r }} ★</option>
        </select>
      </div>
      <div class="col-md-2">
        <input
          v-model="startDate"
          type="date"
          class="form-control"
          aria-label="Filter start date"
        />
      </div>
      
    </div>

    <!-- Reviews Cards -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <div class="col" v-for="review in paginatedReviews" :key="review.id">
        <div class="card h-100 shadow-sm">
          <img
            :src="review.cover"
            class="card-img-top"
            :alt="`Cover of ${review.title}`"
          />
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">{{ review.title }}</h5>
            <p class="card-text flex-grow-1">{{ review.content }}</p>
            <div class="mb-2">
              <span v-for="n in review.rating" :key="`filled-${n}`" class="text-warning">★</span>
              <span v-for="n in 5 - review.rating" :key="`empty-${n}`" class="text-muted">☆</span>
            </div>
            <div class="mt-auto">
              <button
                @click="like(review)"
                class="btn btn-outline-primary btn-sm me-2"
                aria-label="Like review"
              >
                👍 {{ review.likes }}
              </button>
              <button
                @click="dislike(review)"
                class="btn btn-outline-danger btn-sm"
                aria-label="Dislike review"
              >
                👎 {{ review.dislikes }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination Controls -->
    <nav aria-label="Review pages" class="mt-4">
      <ul class="pagination justify-content-center">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <button class="page-link" @click="changePage(currentPage - 1)" aria-label="Previous page">Previous</button>
        </li>
        <li
          v-for="page in totalPages"
          :key="page"
          class="page-item"
          :class="{ active: currentPage === page }"
        >
          <button class="page-link" @click="changePage(page)" :aria-label="`Page ${page}`">{{ page }}</button>
        </li>
        <li :class="{ disabled: currentPage === totalPages }" class="page-item">
          <button class="page-link" @click="changePage(currentPage + 1)" aria-label="Next page">Next</button>
        </li>
      </ul>
    </nav>

    <p v-if="filteredReviews.length === 0" class="text-center mt-4">No reviews match your criteria.</p>
  </div>
</template>

<script>
import reviewsData from '../data/reviews.json'
export default {
  name: 'News',
  data() {
    return {
      reviews: reviewsData.map(r => ({
        ...r,
        likes: r.likes || 0,
        dislikes: r.dislikes || 0
      })),
      searchQuery: '',
      selectedGenre: 'All',
      selectedRating: 'All',
      startDate: '',
      endDate: '',
      currentPage: 1,
      pageSize: 6
    }
  },
  computed: {
    genres() {
      return [...new Set(this.reviews.map(r => r.category))]
    },
    ratings() {
      return [...new Set(this.reviews.map(r => r.rating))].sort((a, b) => a - b)
    },
    filteredReviews() {
      return this.reviews.filter(r => {
        const text = this.searchQuery.trim().toLowerCase()
        const matchesText = r.title.toLowerCase().includes(text) || r.content.toLowerCase().includes(text)
        const matchesGenre = this.selectedGenre === 'All' || r.category === this.selectedGenre
        const matchesRating = this.selectedRating === 'All' || r.rating === this.selectedRating
        const date = new Date(r.date)
        const afterStart = this.startDate ? date >= new Date(this.startDate) : true
        const beforeEnd = this.endDate ? date <= new Date(this.endDate) : true
        return matchesText && matchesGenre && matchesRating && afterStart && beforeEnd
      })
    },
    totalPages() {
      return Math.ceil(this.filteredReviews.length / this.pageSize)
    },
    paginatedReviews() {
      const start = (this.currentPage - 1) * this.pageSize
      return this.filteredReviews.slice(start, start + this.pageSize)
    }
  },
  methods: {
    like(review) {
      review.likes++
    },
    dislike(review) {
      review.dislikes++
    },
    changePage(page) {
      if (page < 1 || page > this.totalPages) return
      this.currentPage = page
    }
  },
  watch: {
    filteredReviews() {
      this.currentPage = 1
    }
  }
}
</script>

<style scoped>
.card-title {
  font-size: 1.25rem;
  margin-bottom: 0.5rem;
}
.card-text {
  font-size: 0.9rem;
  color: #555;
}
</style>
