<template>
  <SellerLayout title="My Products" subtitle="Manage your product listings">

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
      <div
        v-for="stat in productStats"
        :key="stat.label"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-3"
      >
        <span class="text-2xl">{{ stat.icon }}</span>
        <div>
          <p class="text-xl font-black text-gray-800">{{ stat.value }}</p>
          <p class="text-xs text-gray-400">{{ stat.label }}</p>
        </div>
      </div>
    </div>

    <!-- Search + Filter + Add -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-4">
      <div class="flex flex-col md:flex-row md:items-center justify-between px-5 py-4 gap-3 border-b border-gray-100">
        <h3 class="text-base font-black text-gray-800">All Products</h3>
        <div class="flex items-center gap-3">
          <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-3 py-2">
            <span class="text-gray-400 text-sm">🔍</span>
            <input
              v-model="search"
              type="text"
              placeholder="Search products..."
              class="bg-transparent text-sm outline-none text-gray-700 placeholder:text-gray-400 w-36"
            />
          </div>
          <select v-model="categoryFilter" class="text-sm border border-gray-200 rounded-xl px-3 py-2 outline-none bg-white">
            <option value="all">All Categories</option>
            <option value="Vegetables">Vegetables</option>
            <option value="Fruits">Fruits</option>
            <option value="Meat & Fish">Meat & Fish</option>
          </select>
          <button
            @click="openAdd"
            class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-4 py-2 rounded-xl transition-colors"
          >
            <span>+</span> Add Product
          </button>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="p-4 flex flex-col gap-3">
        <div v-for="n in 6" :key="n" class="h-16 bg-gray-100 rounded-xl animate-pulse" />
      </div>

      <!-- Desktop Table -->
      <div v-else class="hidden md:block overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Product</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Category</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Price</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Stock</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Status</th>
              <th class="text-left text-xs font-semibold text-gray-500 uppercase px-5 py-3">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-if="filteredProducts.length === 0">
              <td colspan="6" class="text-center text-sm text-gray-400 py-12">No products found</td>
            </tr>
            <tr
              v-for="product in filteredProducts"
              :key="product.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <!-- Product -->
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 bg-gray-100 rounded-xl overflow-hidden shrink-0 flex items-center justify-center">
                    <img
                      v-if="imageMap[product.name]"
                      :src="imageMap[product.name]"
                      :alt="product.name"
                      class="w-full h-full object-contain p-1"
                    />
                    <span v-else class="text-lg">🥬</span>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-gray-800">{{ product.name }}</p>
                    <p class="text-xs text-gray-400">{{ product.sku }}</p>
                  </div>
                </div>
              </td>
              <!-- Category -->
              <td class="px-5 py-4 text-sm text-gray-600">{{ product.category?.name || '—' }}</td>
              <!-- Price -->
              <td class="px-5 py-4">
                <p class="text-sm font-bold text-gray-800">₱{{ Number(product.price).toFixed(2) }}</p>
                <p v-if="product.original_price" class="text-xs text-gray-400 line-through">₱{{ Number(product.original_price).toFixed(2) }}</p>
              </td>
              <!-- Stock -->
              <td class="px-5 py-4">
                <span :class="[
                  'text-sm font-bold',
                  product.stock <= 5 ? 'text-red-500' : product.stock <= 20 ? 'text-yellow-500' : 'text-gray-800'
                ]">{{ product.stock }}</span>
                <span class="text-xs text-gray-400 ml-1">units</span>
              </td>
              <!-- Status -->
              <td class="px-5 py-4">
                <span :class="[
                  'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold capitalize',
                  product.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'
                ]">
                  {{ product.status }}
                </span>
              </td>
              <!-- Actions -->
              <td class="px-5 py-4">
                <div class="flex items-center gap-2">
                  <button
                    @click="openEdit(product)"
                    class="text-xs bg-blue-100 hover:bg-blue-200 text-blue-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                  >
                    Edit
                  </button>
                  <button
                    @click="handleToggle(product)"
                    :class="[
                      'text-xs font-semibold px-3 py-1.5 rounded-full transition-colors',
                      product.status === 'active'
                        ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-600'
                        : 'bg-green-100 hover:bg-green-200 text-green-600'
                    ]"
                  >
                    {{ product.status === 'active' ? 'Deactivate' : 'Activate' }}
                  </button>
                  <button
                    @click="handleDelete(product.id)"
                    class="text-xs bg-red-100 hover:bg-red-200 text-red-600 font-semibold px-3 py-1.5 rounded-full transition-colors"
                  >
                    Delete
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Mobile Cards -->
      <div class="md:hidden p-4 flex flex-col gap-3">
        <div v-if="filteredProducts.length === 0" class="text-center text-sm text-gray-400 py-8">No products found</div>
        <div
          v-for="product in filteredProducts"
          :key="product.id"
          class="border border-gray-100 rounded-xl p-4 flex items-center gap-3"
        >
          <div class="w-12 h-12 bg-gray-100 rounded-xl shrink-0 flex items-center justify-center overflow-hidden">
            <img v-if="imageMap[product.name]" :src="imageMap[product.name]" :alt="product.name" class="w-full h-full object-contain p-1" />
            <span v-else class="text-xl">🥬</span>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800 truncate">{{ product.name }}</p>
            <p class="text-xs text-gray-400">₱{{ Number(product.price).toFixed(2) }} · Stock: {{ product.stock }}</p>
            <span :class="['text-[10px] font-semibold px-2 py-0.5 rounded-full capitalize', product.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500']">
              {{ product.status }}
            </span>
          </div>
          <div class="flex flex-col gap-1 shrink-0">
            <button @click="openEdit(product)" class="text-xs bg-blue-100 text-blue-600 font-semibold px-3 py-1 rounded-full">Edit</button>
            <button @click="handleDelete(product.id)" class="text-xs bg-red-100 text-red-600 font-semibold px-3 py-1 rounded-full">Delete</button>
          </div>
        </div>
      </div>

    </div>

    <!-- Product Form Modal -->
    <ProductFormModal
      :show="showModal"
      :product="editingProduct"
      :categories="categories"
      @close="closeModal"
      @saved="handleSaved"
    />

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import SellerLayout from '../../../components/seller/layout/SellerLayout.vue'
import ProductFormModal from '../../../components/seller/products/ProductFormModal.vue'

const { get, put, destroy } = useApi()

const products = ref<any[]>([])
const categories = ref<any[]>([])
const loading = ref(false)
const search = ref('')
const categoryFilter = ref('all')
const showModal = ref(false)
const editingProduct = ref<any>(null)

const imageMap: Record<string, string> = {
  'Tomato':        '/images/products/vegetables/Tomato.png',
  'Eggplant':      '/images/products/vegetables/eggplant.png',
  'Bitter Gourd':  '/images/products/vegetables/bitter_gourd.png',
  'Okra':          '/images/products/vegetables/okra.png',
  'Sitaw':         '/images/products/vegetables/sitaw.png',
  'Kangkong':      '/images/products/vegetables/kangkong.png',
  'Repolyo':       '/images/products/vegetables/repolyo.png',
  'Carrot':        '/images/products/vegetables/carrot.png',
  'Potato':        '/images/products/vegetables/potato.png',
  'Sibuyas':       '/images/products/vegetables/sibuyas.png',
  'Bawang':        '/images/products/vegetables/bawang.png',
  'Luya':          '/images/products/vegetables/luya.png',
  'Mais':          '/images/products/vegetables/mais.png',
  'Siling Haba':   '/images/products/vegetables/siling_haba.png',
  'Siling Labuyo': '/images/products/vegetables/siling_labuyo.png',
  'Upo':           '/images/products/vegetables/upo.png',
  'Patola':        '/images/products/vegetables/patola.png',
  'Sigarilyas':    '/images/products/vegetables/sigarilyas.png',
  'Gabi':          '/images/products/vegetables/gabi.png',
  'Kamote':        '/images/products/vegetables/kamote.png',
  'Labanos':       '/images/products/vegetables/labanos.png',
  'Banana':        '/images/products/fruits/saging.png',
  'Mango':         '/images/products/fruits/mango.png',
  'Papaya':        '/images/products/fruits/papaya.png',
  'Pakwan':        '/images/products/fruits/pakwan.png',
  'Bayabas':       '/images/products/fruits/Guava.png',
  'Melon':         '/images/products/fruits/melon.png',
  'Pineapple':     '/images/products/fruits/Pineapple.png',
  'Avocado':       '/images/products/fruits/Avocado.png',
  'Guava':         '/images/products/fruits/Guava.png',
  'Rambutan':      '/images/products/fruits/Rambutan.png',
  'Lanzones':      '/images/products/fruits/Lanzones.jpg',
  'Calamansi':     '/images/products/fruits/Calamansi.png',
  'Orange':        '/images/products/fruits/Orange.png',
  'Apple':         '/images/products/fruits/Apple.png',
  'Grapes':        '/images/products/fruits/Grapes.png',
  'Chicken':       '/images/products/meat/Chicken.png',
  'Pork Meat':     '/images/products/meat/pork_meat.png',
  'Egg':           '/images/products/meat/Egg.png',
  'Rice':          '/images/products/meat/rice.png',
  'Bangus':        '/images/products/meat/Chicken.png',
  'Tilapia':       '/images/products/meat/Chicken.png',
  'Hipon':         '/images/products/meat/Chicken.png',
}

const loadProducts = async () => {
  loading.value = true
  try {
    const res: any = await get('/products', { per_page: 100 })
    products.value = res.data || []
  } catch (e) {
    console.error('Failed to load products', e)
  } finally {
    loading.value = false
  }
}

const loadCategories = async () => {
  try {
    const res: any = await get('/categories')
    categories.value = res || []
  } catch (e) {
    console.error('Failed to load categories', e)
  }
}

onMounted(() => {
  loadProducts()
  loadCategories()
})

const productStats = computed(() => [
  { icon: '🛍️', label: 'Total Products', value: products.value.length },
  { icon: '✅', label: 'Active',          value: products.value.filter(p => p.status === 'active').length },
  { icon: '⛔', label: 'Inactive',        value: products.value.filter(p => p.status === 'inactive').length },
  { icon: '⚠️', label: 'Low Stock',       value: products.value.filter(p => p.stock <= 10).length },
])

const filteredProducts = computed(() => {
  let result = [...products.value]
  if (categoryFilter.value !== 'all') {
    result = result.filter(p => p.category?.name === categoryFilter.value)
  }
  if (search.value) {
    result = result.filter(p =>
      p.name?.toLowerCase().includes(search.value.toLowerCase()) ||
      p.sku?.toLowerCase().includes(search.value.toLowerCase())
    )
  }
  return result
})

const openAdd = () => {
  editingProduct.value = null
  showModal.value = true
}

const openEdit = (product: any) => {
  editingProduct.value = product
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  editingProduct.value = null
}

const handleSaved = (savedProduct: any) => {
  const index = products.value.findIndex(p => p.id === savedProduct.id)
  if (index !== -1) {
    products.value[index] = savedProduct
  } else {
    products.value.unshift(savedProduct)
  }
  closeModal()
}

const handleToggle = async (product: any) => {
  const newStatus = product.status === 'active' ? 'inactive' : 'active'
  try {
    await put(`/products/${product.id}`, { status: newStatus })
    product.status = newStatus
  } catch (e) {
    console.error('Failed to toggle product status', e)
  }
}

const handleDelete = async (id: number) => {
  if (!confirm('Are you sure you want to delete this product?')) return
  try {
    await destroy(`/products/${id}`)
    products.value = products.value.filter(p => p.id !== id)
  } catch (e) {
    console.error('Failed to delete product', e)
  }
}
</script>