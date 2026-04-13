<template>
  <SellerLayout title="Help Center" subtitle="Find answers to common questions">

    <!-- Search -->
    <div class="max-w-xl mb-8">
      <div class="flex items-center gap-3 bg-white border-2 border-gray-200 rounded-2xl px-4 py-3 focus-within:border-green-500 transition-colors">
        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input
          v-model="search"
          type="text"
          placeholder="Search for help..."
          class="flex-1 text-sm outline-none text-gray-800 placeholder-gray-400 bg-transparent"
        />
      </div>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
      <div v-for="link in quickLinks" :key="link.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex flex-col items-center gap-2 text-center cursor-pointer hover:border-green-400 transition-colors"
        @click="activeCategory = link.category"
      >
        <span class="text-3xl">{{ link.icon }}</span>
        <p class="text-sm font-semibold text-gray-700">{{ link.label }}</p>
      </div>
    </div>

    <!-- FAQ Section -->
    <div class="max-w-2xl">
      <h3 class="text-base font-black text-gray-800 mb-4">
        {{ activeCategory ? activeCategory : 'Frequently Asked Questions' }}
      </h3>

      <div class="flex flex-col gap-3">
        <div
          v-for="faq in filteredFaqs"
          :key="faq.question"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden"
        >
          <button
            @click="faq.open = !faq.open"
            class="w-full flex items-center justify-between px-5 py-4 text-left"
          >
            <span class="text-sm font-semibold text-gray-800">{{ faq.question }}</span>
            <svg
              class="w-4 h-4 text-gray-400 shrink-0 transition-transform"
              :class="faq.open ? 'rotate-180' : ''"
              fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>
          <div v-if="faq.open" class="px-5 pb-4 text-sm text-gray-500 leading-relaxed border-t border-gray-50 pt-3">
            {{ faq.answer }}
          </div>
        </div>

        <div v-if="filteredFaqs.length === 0" class="text-center py-10 text-gray-400 text-sm">
          No results found for "{{ search }}"
        </div>
      </div>
    </div>

    <!-- Contact Support -->
    <div class="max-w-2xl mt-8 bg-[#0f2d1f] rounded-2xl p-6 text-white">
      <h3 class="text-base font-black mb-1">Still need help?</h3>
      <p class="text-green-200 text-sm mb-4">Our support team is ready to assist you.</p>
      <div class="flex flex-wrap gap-3">
        <a href="tel:+639660818437" class="flex items-center gap-2 bg-green-500 hover:bg-green-400 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
          📞 Call Support
        </a>
        <a href="mailto:support@obra-ur.xyz" class="flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
          ✉️ Email Us
        </a>
      </div>
    </div>

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed, reactive } from 'vue'
import SellerLayout from '../../components/seller/layout/SellerLayout.vue'

const search = ref('')
const activeCategory = ref('')

const quickLinks = [
  { icon: '📦', label: 'Managing Orders',  category: 'Orders'   },
  { icon: '🛍️', label: 'Products',         category: 'Products' },
  { icon: '💰', label: 'Payments',         category: 'Payments' },
  { icon: '🚚', label: 'Delivery',         category: 'Delivery' },
]

const faqs = reactive([
  { category: 'Orders',   open: false, question: 'How do I accept an order?',                    answer: 'Go to the Orders page. When a customer places an order it will appear as Pending. Click Confirm to accept it, then update the status as you prepare and pack the items.' },
  { category: 'Orders',   open: false, question: 'Can I cancel an order?',                       answer: 'Yes. You can cancel a Pending or Confirmed order. Once an order is In Transit or Delivered it can no longer be cancelled. Go to Orders, open the order and click Cancel.' },
  { category: 'Orders',   open: false, question: 'What happens after I mark an order as Ready?', answer: 'Once you mark an order as Ready, available drivers near you will be notified. A driver will then accept the order and pick it up from your store for delivery.' },
  { category: 'Products', open: false, question: 'How do I add a new product?',                  answer: 'Go to the Products page and click Add Product. Fill in the product name, category, price, stock, and upload up to 5 images. Click Save to publish your product.' },
  { category: 'Products', open: false, question: 'How many images can I upload per product?',    answer: 'You can upload up to 5 images per product. The first image will be used as the main display image on the shop page.' },
  { category: 'Products', open: false, question: 'How do I deactivate a product?',               answer: 'Go to Products, click Edit on the product, and change the Status from Active to Inactive. The product will be hidden from customers but not deleted.' },
  { category: 'Payments', open: false, question: 'What payment methods are supported?',          answer: 'OBRA currently supports GCash and Cash on Delivery (COD). Customers choose their preferred method when placing an order.' },
  { category: 'Payments', open: false, question: 'When do I receive my payment?',                answer: 'For COD orders, the driver collects cash from the customer and remits it to you. For GCash orders, payment is processed digitally. Revenue is tracked in your Revenue page.' },
  { category: 'Delivery', open: false, question: 'Who handles the delivery?',                    answer: 'OBRA has a network of registered drivers. Once you mark your order as Ready, a driver will be assigned to pick up and deliver the order to the customer.' },
  { category: 'Delivery', open: false, question: 'What is the delivery fee?',                    answer: 'The standard delivery fee is ₱50 per order within Urdaneta City. This fee is charged to the customer, not the seller.' },
])

const filteredFaqs = computed(() => {
  return faqs.filter(f => {
    const matchesSearch = !search.value ||
      f.question.toLowerCase().includes(search.value.toLowerCase()) ||
      f.answer.toLowerCase().includes(search.value.toLowerCase())
    const matchesCategory = !activeCategory.value || f.category === activeCategory.value
    return matchesSearch && matchesCategory
  })
})
</script>