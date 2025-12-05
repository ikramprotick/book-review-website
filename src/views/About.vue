<template>
  <section class="container my-5" aria-labelledby="about-heading">
    <h2 id="about-heading">About BookWise</h2>
    <p>This platform lets readers share honest reviews and discover new reads.</p>

    <form class="row g-3" @submit.prevent>
      <!-- Name Inputs -->
      <div class="col-md-6">
        <label for="firstName" class="form-label">First Name</label>
        <input
          id="firstName"
          v-model="first"
          type="text"
          class="form-control"
          placeholder="Enter your first name"
          aria-required="true"
        />
      </div>
      <div class="col-md-6">
        <label for="lastName" class="form-label">Last Name</label>
        <input
          id="lastName"
          v-model="last"
          type="text"
          class="form-control"
          placeholder="Enter your last name"
          aria-required="true"
        />
      </div>
      <div class="col-12">
        <p
          v-if="fullName"
          class="alert alert-success"
          role="status"
          aria-live="polite"
        >
          Welcome, {{ fullName }}!
        </p>
      </div>

      <!-- Background Selection -->
      <fieldset class="col-12" aria-labelledby="scene-group">
        <legend id="scene-group" class="form-label">Choose Background</legend>
        <div class="form-check form-check-inline">
          <input
            id="mountain"
            v-model="scene"
            type="radio"
            name="scene"
            value="Mountain"
            class="form-check-input"
          />
          <label for="mountain" class="form-check-label">Mountain</label>
        </div>
        <div class="form-check form-check-inline">
          <input
            id="ocean"
            v-model="scene"
            type="radio"
            name="scene"
            value="Ocean"
            class="form-check-input"
          />
          <label for="ocean" class="form-check-label">Ocean</label>
        </div>
      </fieldset>

      <!-- Clear Button -->
      <div class="col-12">
        <button
          type="button"
          v-if="scene"
          @click="clearScene"
          class="btn btn-outline-secondary mt-3"
        >
          Clear Selection
        </button>
      </div>
    </form>

    <!-- Mascot Preview -->
    <div v-if="scene" class="mt-4 text-center">
      <img
        :src="scene === 'Mountain' ? mascot1 : mascot2"
        class="img-fluid rounded"
        :alt="scene === 'Mountain' ? 'Mountain Mascot' : 'Ocean Mascot'"
      />
    </div>
  </section>
</template>

<script>
import mascot1 from '@/assets/mascot1.png'
import mascot2 from '@/assets/mascot2.png'

export default {
  name: 'About',
  data() {
    return {
      first: '',
      last: '',
      scene: '',
      mascot1,
      mascot2
    }
  },
  computed: {
    fullName() {
      return [this.first, this.last].filter(Boolean).join(' ')
    }
  },
  methods: {
    clearScene() {
      this.scene = ''
    }
  }
}
</script>

<style scoped>
.form-label {
  font-weight: 500;
}
</style>
