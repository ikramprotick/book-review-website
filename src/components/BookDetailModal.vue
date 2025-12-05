<template>
  <div>
    <!-- Modal dialog -->
    <div
      class="modal fade show"
      tabindex="-1"
      style="display: block;"
      role="dialog"
      aria-modal="true"
    >
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

          <!-- Header -->
          <div class="modal-header">
            <h5 class="modal-title">{{ book.title }}</h5>
            <button
              type="button"
              class="btn-close"
              aria-label="Close"
              @click="$emit('close')"
            ></button>
          </div>

          <!-- Body -->
          <div class="modal-body">
            <img :src="book.cover" class="img-fluid mb-3" :alt="book.title" />

            <p class="mb-3"><strong>Abstract:</strong> {{ book.abstract }}</p>
            <p><strong>Author:</strong> {{ book.author }}</p>
            <p><strong>Genre:</strong> {{ book.genre }}</p>

            <hr />

            <h6>Reviews</h6>
            <div v-if="reviews.length">
              <div
                v-for="r in reviews"
                :key="r.id"
                class="border p-2 mb-2 rounded"
              >
                <p class="mb-1">{{ r.content }}</p>
                <p class="small mb-0">
                  Rating:
                  <span v-for="n in 5" :key="n">
                    <i
                      class="bi"
                      :class="n <= r.rating ? 'bi-star-fill text-warning' : 'bi-star text-muted'"
                    ></i>
                  </span>
                  — by User {{ r.authorId }}
                </p>
              </div>
            </div>
            <p v-else>No reviews yet.</p>

            <hr />

            <div v-if="isAuth">
              <h6>Leave a Review</h6>
              <textarea
                v-model="newReview"
                class="form-control mb-2"
                rows="3"
                placeholder="Your thoughts…"
              ></textarea>

              <!-- Star Rating Input -->
              <div class="mb-3">
                <label class="form-label">Your Rating:</label>
                <div class="d-flex">
                  <label
                    v-for="n in 5"
                    :key="n"
                    class="me-2"
                    :title="`${n} Star${n>1?'s':''}`"
                  >
                    <input
                      type="radio"
                      :value="n"
                      v-model="newRating"
                      class="visually-hidden"
                    />
                    <i
                      class="bi"
                      :class="n <= newRating ? 'bi-star-fill text-warning' : 'bi-star text-muted'"
                      style="font-size: 1.5rem; cursor: pointer;"
                    ></i>
                  </label>
                </div>
              </div>

              <button class="btn btn-primary" @click="submitReview">
                Submit Review
              </button>
            </div>
            <p v-else>
              <router-link to="/login">Log in</router-link> to leave a review.
            </p>
          </div>

        </div>
      </div>
    </div>

    <!-- Backdrop -->
    <div class="modal-backdrop fade show"></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { storeToRefs } from 'pinia'
import { useAuthStore } from '@/stores/auth'

const props = defineProps({
  book: { type: Object, required: true }
})
const emit = defineEmits(['close'])

const auth = useAuthStore()
const { isAuthenticated: isAuth } = storeToRefs(auth)

const reviews = ref([])
const newReview = ref('')
const newRating = ref(5)

onMounted(async () => {
  const res = await axios.get('/api/reviews.php', { withCredentials: true })
  reviews.value = res.data.filter(r => r.bookId === props.book.id)
})

async function submitReview() {
  if (!newReview.value.trim()) return
  const payload = {
    bookId: props.book.id,
    content: newReview.value,
    rating: newRating.value
  }
  const res = await axios.post('/api/reviews.php', payload, { withCredentials: true })
  reviews.value.push(res.data)
  newReview.value = ''
  newRating.value = 5
}
</script>

<style scoped>
.modal-body img {
  max-height: 200px;
  object-fit: cover;
}
</style>
