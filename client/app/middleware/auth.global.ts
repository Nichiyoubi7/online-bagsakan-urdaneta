export default defineNuxtRouteMiddleware((to) => {
  if (!import.meta.client) return

  const authStore = useAuthStore()

  // Always load fresh from localStorage
  authStore.loadFromStorage()

  const role = authStore.role
  const isLoggedIn = authStore.isLoggedIn
  const path = to.path

  const isAdminRoute  = path.startsWith('/admin')
  const isSellerRoute = path.startsWith('/seller')
  const isDriverRoute = path.startsWith('/driver')
  const isStaffRoute  = path.startsWith('/staff')

  // Not logged in — block all dashboard routes
  if (!isLoggedIn) {
    if (isAdminRoute || isSellerRoute || isDriverRoute || isStaffRoute) {
      return navigateTo('/')
    }
    return
  }

  // ADMIN or STAFF
  if (role === 'admin' || role === 'staff') {
    if (isSellerRoute || isDriverRoute) {
      return navigateTo('/admin/dashboard')
    }
    // Redirect from any public/customer page back to admin dashboard
    if (!isAdminRoute && !isStaffRoute) {
      return navigateTo('/admin/dashboard')
    }
  }

  // SELLER
  if (role === 'seller') {
    if (isAdminRoute || isDriverRoute || isStaffRoute) {
      return navigateTo('/seller/dashboard')
    }
    if (path === '/') {
      return navigateTo('/seller/dashboard')
    }
  }

  // DRIVER
  if (role === 'driver') {
    if (isAdminRoute || isSellerRoute || isStaffRoute) {
      return navigateTo('/driver/dashboard')
    }
    if (path === '/') {
      return navigateTo('/driver/dashboard')
    }
  }

  // CUSTOMER
  if (role === 'customer') {
    if (isAdminRoute || isSellerRoute || isDriverRoute || isStaffRoute) {
      return navigateTo('/')
    }
  }
})