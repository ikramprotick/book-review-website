// src/stores/cart.js
import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: []    // array of book objects added to the cart
  }),
  getters: {
    count: state => state.items.length
  },
  actions: {
    /**
     * Add a book to the cart (no duplicates)
     */
    addToCart(book) {
      if (!this.items.find(b => b.id === book.id)) {
        this.items.push(book)
      }
    },
    /**
     * (Optional) Remove a book from the cart
     */
    removeFromCart(bookId) {
      this.items = this.items.filter(b => b.id !== bookId)
    },
    /**
     * (Optional) Clear the entire cart
     */
    clearCart() {
      this.items = []
    }
  }
})
