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
  { id: 1, name: 'Green Apple',     sku: 'SKU-001', category: 'Fresh Fruit', price: 15.00, originalPrice: 25.00,  stock: 150, status: 'Active',   image: '/images/products/green-apple.png'     },
  { id: 2, name: 'Guijapur Mango',  sku: 'SKU-002', category: 'Fresh Fruit', price: 15.00, originalPrice: 20.00,  stock: 80,  status: 'Active',   image: '/images/products/mango.png'           },
  { id: 3, name: 'Red Tomatoes',    sku: 'SKU-003', category: 'Vegetables',  price: 15.00, originalPrice: 20.00,  stock: 5,   status: 'Active',   image: '/images/products/tomatoes.png'        },
  { id: 4, name: 'Chinese Cabbage', sku: 'SKU-004', category: 'Vegetables',  price: 15.00, originalPrice: 42.00,  stock: 60,  status: 'Active',   image: '/images/products/chinese-cabbage.png' },
  { id: 5, name: 'Green Lettuce',   sku: 'SKU-005', category: 'Vegetables',  price: 15.00, originalPrice: 18.00,  stock: 3,   status: 'Active',   image: '/images/products/lettuce.png'         },
  { id: 6, name: 'Eggplant',        sku: 'SKU-006', category: 'Vegetables',  price: 15.00, originalPrice: 20.00,  stock: 45,  status: 'Inactive', image: '/images/products/eggplant.png'        },
  { id: 7, name: 'Green Chili',     sku: 'SKU-007', category: 'Vegetables',  price: 15.00, originalPrice: 18.00,  stock: 90,  status: 'Active',   image: '/images/products/green-chili.png'     },
  { id: 8, name: 'Bangus',          sku: 'SKU-008', category: 'Meat & Fish', price: 85.00, originalPrice: 100.00, stock: 30,  status: 'Active',   image: '/images/products/bangus.png'          },
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