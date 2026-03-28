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

    <!-- Product Table -->
    <ProductTable
      :products="products"
      @add="showModal = true; editingProduct = null"
      @edit="handleEdit"
      @toggle="handleToggle"
      @delete="handleDelete"
    />

    <!-- Add/Edit Modal -->
    <ProductFormModal
      :show="showModal"
      :product="editingProduct"
      @close="showModal = false; editingProduct = null"
      @save="handleSave"
    />

  </SellerLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import SellerLayout from '../../../components/seller/layout/SellerLayout.vue'
import ProductTable from '../../../components/seller/products/ProductTable.vue'
import ProductFormModal from '../../../components/seller/products/ProductFormModal.vue'

const showModal = ref(false)
const editingProduct = ref(null)

const products = ref([
  // Vegetables
  { id: 1,  name: 'Tomato',         sku: 'SKU-001', category: 'Vegetables', price: 20.00, originalPrice: 25.00, stock: 100, status: 'Active', image: '/images/products/vegetables/Tomato.png' },
  { id: 2,  name: 'Eggplant',       sku: 'SKU-002', category: 'Vegetables', price: 15.00, originalPrice: 20.00, stock: 80,  status: 'Active', image: '/images/products/vegetables/eggplant.png' },
  { id: 3,  name: 'Bitter Gourd',   sku: 'SKU-003', category: 'Vegetables', price: 18.00, originalPrice: 22.00, stock: 60,  status: 'Active', image: '/images/products/vegetables/bitter_gourd.png' },
  { id: 4,  name: 'Okra',           sku: 'SKU-004', category: 'Vegetables', price: 12.00, originalPrice: 15.00, stock: 90,  status: 'Active', image: '/images/products/vegetables/okra.png' },
  { id: 5,  name: 'Sitaw',          sku: 'SKU-005', category: 'Vegetables', price: 15.00, originalPrice: 18.00, stock: 70,  status: 'Active', image: '/images/products/vegetables/sitaw.png' },
  { id: 6,  name: 'Kangkong',       sku: 'SKU-006', category: 'Vegetables', price: 10.00, originalPrice: 12.00, stock: 120, status: 'Active', image: '/images/products/vegetables/kangkong.png' },
  { id: 7,  name: 'Repolyo',        sku: 'SKU-007', category: 'Vegetables', price: 25.00, originalPrice: 30.00, stock: 50,  status: 'Active', image: '/images/products/vegetables/repolyo.png' },
  { id: 8,  name: 'Bok Choy',       sku: 'SKU-008', category: 'Vegetables', price: 15.00, originalPrice: 18.00, stock: 65,  status: 'Active', image: '/images/products/vegetables/bok_choy.png' },
  { id: 9,  name: 'Potato',         sku: 'SKU-009', category: 'Vegetables', price: 30.00, originalPrice: 35.00, stock: 85,  status: 'Active', image: '/images/products/vegetables/potato.png' },
  { id: 10, name: 'Carrot',         sku: 'SKU-010', category: 'Vegetables', price: 25.00, originalPrice: 30.00, stock: 75,  status: 'Active', image: '/images/products/vegetables/carrot.png' },
  { id: 11, name: 'Sayote',         sku: 'SKU-011', category: 'Vegetables', price: 12.00, originalPrice: 15.00, stock: 95,  status: 'Active', image: '/images/products/vegetables/sayote.png' },
  { id: 12, name: 'Petchay Baguio', sku: 'SKU-012', category: 'Vegetables', price: 20.00, originalPrice: 25.00, stock: 55,  status: 'Active', image: '/images/products/vegetables/petchay_baguio.png' },
  { id: 13, name: 'Sibuyas',        sku: 'SKU-013', category: 'Vegetables', price: 60.00, originalPrice: 70.00, stock: 40,  status: 'Active', image: '/images/products/vegetables/sibuyas.png' },
  { id: 14, name: 'Bawang',         sku: 'SKU-014', category: 'Vegetables', price: 80.00, originalPrice: 90.00, stock: 35,  status: 'Active', image: '/images/products/vegetables/bawang.png' },
  { id: 15, name: 'Luya',           sku: 'SKU-015', category: 'Vegetables', price: 40.00, originalPrice: 50.00, stock: 45,  status: 'Active', image: '/images/products/vegetables/luya.png' },
  { id: 16, name: 'Mais',           sku: 'SKU-016', category: 'Vegetables', price: 15.00, originalPrice: 20.00, stock: 110, status: 'Active', image: '/images/products/vegetables/mais.png' },
  { id: 17, name: 'Siling Haba',    sku: 'SKU-017', category: 'Vegetables', price: 20.00, originalPrice: 25.00, stock: 88,  status: 'Active', image: '/images/products/vegetables/siling_haba.png' },
  { id: 18, name: 'Siling Labuyo',  sku: 'SKU-018', category: 'Vegetables', price: 25.00, originalPrice: 30.00, stock: 66,  status: 'Active', image: '/images/products/vegetables/siling_labuyo.png' },
  { id: 19, name: 'Upo',            sku: 'SKU-019', category: 'Vegetables', price: 15.00, originalPrice: 18.00, stock: 72,  status: 'Active', image: '/images/products/vegetables/upo.png' },
  { id: 20, name: 'Patola',         sku: 'SKU-020', category: 'Vegetables', price: 12.00, originalPrice: 15.00, stock: 58,  status: 'Active', image: '/images/products/vegetables/patola.png' },
  { id: 21, name: 'Sigarilyas',     sku: 'SKU-021', category: 'Vegetables', price: 12.00, originalPrice: 15.00, stock: 62,  status: 'Active', image: '/images/products/vegetables/sigarilyas.png' },
  { id: 22, name: 'Labanos',        sku: 'SKU-022', category: 'Vegetables', price: 15.00, originalPrice: 18.00, stock: 55,  status: 'Active', image: '/images/products/vegetables/labanos.png' },
  { id: 23, name: 'Gabi',           sku: 'SKU-023', category: 'Vegetables', price: 20.00, originalPrice: 25.00, stock: 48,  status: 'Active', image: '/images/products/vegetables/gabi.png' },
  { id: 24, name: 'Kamote',         sku: 'SKU-024', category: 'Vegetables', price: 18.00, originalPrice: 22.00, stock: 70,  status: 'Active', image: '/images/products/vegetables/kamote.png' },

  // Fruits
  { id: 25, name: 'Mango',          sku: 'SKU-025', category: 'Fruits', price: 50.00, originalPrice: 60.00,  stock: 100, status: 'Active', image: '/images/products/fruits/mango.png' },
  { id: 26, name: 'Saging',         sku: 'SKU-026', category: 'Fruits', price: 30.00, originalPrice: 35.00,  stock: 90,  status: 'Active', image: '/images/products/fruits/saging.png' },
  { id: 27, name: 'Papaya',         sku: 'SKU-027', category: 'Fruits', price: 40.00, originalPrice: 50.00,  stock: 70,  status: 'Active', image: '/images/products/fruits/papaya.png' },
  { id: 28, name: 'Pakwan',         sku: 'SKU-028', category: 'Fruits', price: 80.00, originalPrice: 100.00, stock: 40,  status: 'Active', image: '/images/products/fruits/pakwan.png' },
  { id: 29, name: 'Melon',          sku: 'SKU-029', category: 'Fruits', price: 70.00, originalPrice: 85.00,  stock: 45,  status: 'Active', image: '/images/products/fruits/melon.png' },
  { id: 30, name: 'Pineapple',      sku: 'SKU-030', category: 'Fruits', price: 45.00, originalPrice: 55.00,  stock: 60,  status: 'Active', image: '/images/products/fruits/Pineapple.png' },
  { id: 31, name: 'Avocado',        sku: 'SKU-031', category: 'Fruits', price: 60.00, originalPrice: 75.00,  stock: 50,  status: 'Active', image: '/images/products/fruits/Avocado.png' },
  { id: 32, name: 'Guava',          sku: 'SKU-032', category: 'Fruits', price: 25.00, originalPrice: 30.00,  stock: 80,  status: 'Active', image: '/images/products/fruits/Guava.png' },
  { id: 33, name: 'Rambutan',       sku: 'SKU-033', category: 'Fruits', price: 35.00, originalPrice: 45.00,  stock: 55,  status: 'Active', image: '/images/products/fruits/Rambutan.png' },
  { id: 34, name: 'Lanzones',       sku: 'SKU-034', category: 'Fruits', price: 40.00, originalPrice: 50.00,  stock: 48,  status: 'Active', image: '/images/products/fruits/Lanzones.png' },
  { id: 35, name: 'Calamansi',      sku: 'SKU-035', category: 'Fruits', price: 20.00, originalPrice: 25.00,  stock: 95,  status: 'Active', image: '/images/products/fruits/Calamansi.png' },
  { id: 36, name: 'Orange',         sku: 'SKU-036', category: 'Fruits', price: 35.00, originalPrice: 40.00,  stock: 75,  status: 'Active', image: '/images/products/fruits/Orange.png' },
  { id: 37, name: 'Apple',          sku: 'SKU-037', category: 'Fruits', price: 45.00, originalPrice: 55.00,  stock: 65,  status: 'Active', image: '/images/products/fruits/Apple.png' },
  { id: 38, name: 'Grapes',         sku: 'SKU-038', category: 'Fruits', price: 80.00, originalPrice: 95.00,  stock: 40,  status: 'Active', image: '/images/products/fruits/Grapes.png' },

  // Meat & Others
  { id: 39, name: 'Chicken',        sku: 'SKU-039', category: 'Meat & Fish', price: 180.00, originalPrice: 200.00, stock: 50,  status: 'Active', image: '/images/products/meat/Chicken.png' },
  { id: 40, name: 'Pork Meat',      sku: 'SKU-040', category: 'Meat & Fish', price: 220.00, originalPrice: 250.00, stock: 40,  status: 'Active', image: '/images/products/meat/pork_meat.png' },
  { id: 41, name: 'Egg',            sku: 'SKU-041', category: 'Meat & Fish', price: 12.00,  originalPrice: 14.00,  stock: 200, status: 'Active', image: '/images/products/meat/Egg.png' },
  { id: 42, name: 'Rice',           sku: 'SKU-042', category: 'Meat & Fish', price: 55.00,  originalPrice: 60.00,  stock: 150, status: 'Active', image: '/images/products/meat/rice.png' },
])

const productStats = [
  { icon: '🛍️', label: 'Total Products', value: products.value.length },
  { icon: '✅', label: 'Active',          value: products.value.filter(p => p.status === 'Active').length },
  { icon: '⛔', label: 'Inactive',        value: products.value.filter(p => p.status === 'Inactive').length },
  { icon: '⚠️', label: 'Low Stock',       value: products.value.filter(p => p.stock <= 10).length },
]

const handleEdit = (product: any) => {
  editingProduct.value = product
  showModal.value = true
}

const handleToggle = (product: any) => {
  const p = products.value.find(p => p.id === product.id)
  if (p) p.status = p.status === 'Active' ? 'Inactive' : 'Active'
}

const handleDelete = (id: number) => {
  if (confirm('Are you sure you want to delete this product?')) {
    products.value = products.value.filter(p => p.id !== id)
  }
}

const handleSave = (data: any) => {
  if (editingProduct.value) {
    const index = products.value.findIndex(p => p.id === (editingProduct.value as any).id)
    if (index !== -1) {
      products.value[index] = {
        ...products.value[index],
        ...data,
        status: data.active ? 'Active' : 'Inactive',
      }
    }
  } else {
    products.value.push({
      id: Date.now(),
      sku: 'SKU-' + Date.now(),
      status: data.active ? 'Active' : 'Inactive',
      ...data,
    })
  }
}
</script>