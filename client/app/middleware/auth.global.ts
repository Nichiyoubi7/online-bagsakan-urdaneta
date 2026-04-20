export default defineNuxtRouteMiddleware((to) => {
  const authStore = useAuthStore()

  // Load from storage on every navigation
  if (import.meta.client) {
    authStore.loadFromStorage()
  }

  const role = authStore.role
  const isLoggedIn = authStore.isLoggedIn
  const path = to.path

  // Define which roles can access which routes
  const adminRoutes   = path.startsWith('/admin')
  const sellerRoutes  = path.startsWith('/seller')
  const driverRoutes  = path.startsWith('/driver')
  const staffRoutes   = path.startsWith('/staff')
  const customerRoutes = path.startsWith('/customer')

  // Not logged in trying to access protected routes
  if (!isLoggedIn && (adminRoutes || sellerRoutes || driverRoutes || staffRoutes)) {
    return navigateTo('/')
  }

  // Wrong role trying to access wrong dashboard
  if (isLoggedIn) {
    // Admin trying to go to seller/driver/customer pages
    if (role === 'admin' && (sellerRoutes || driverRoutes)) {
      return navigateTo('/admin/dashboard')
    }

    // Seller trying to go to admin/driver/customer account pages
    if (role === 'seller' && (adminRoutes || driverRoutes)) {
      return navigateTo('/seller/dashboard')
    }

    // Driver trying to go to admin/seller pages
    if (role === 'driver' && (adminRoutes || sellerRoutes)) {
      return navigateTo('/driver/dashboard')
    }

    // Staff trying to go to admin/seller/driver pages
    if (role === 'staff' && (adminRoutes || sellerRoutes || driverRoutes)) {
      return navigateTo('/staff/dashboard')
    }

    // Customer trying to go to admin/seller/driver/staff pages
    if (role === 'customer' && (adminRoutes || sellerRoutes || driverRoutes || staffRoutes)) {
      return navigateTo('/')
    }
  }
})