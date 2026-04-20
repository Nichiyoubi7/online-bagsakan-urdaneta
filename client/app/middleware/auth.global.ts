export default defineNuxtRouteMiddleware((to) => {
  const authStore = useAuthStore()

  if (import.meta.client) {
    authStore.loadFromStorage()
  }

  const role = authStore.role
  const isLoggedIn = authStore.isLoggedIn
  const path = to.path

  const adminRoutes    = path.startsWith('/admin')
  const sellerRoutes   = path.startsWith('/seller')
  const driverRoutes   = path.startsWith('/driver')
  const staffRoutes    = path.startsWith('/staff')

  // Guest pages that logged-in users with dashboards shouldn't access
  const guestPages = [
    '/',
    '/sellers',
    '/about',
    '/contact',
    '/customer',
    '/seller-store',
  ]
  const isGuestPage = guestPages.some(p => path === p || path.startsWith(p))

  if (!isLoggedIn) {
    // Not logged in trying to access protected routes
    if (adminRoutes || sellerRoutes || driverRoutes || staffRoutes) {
      return navigateTo('/')
    }
    return
  }

  // Logged in — enforce role-based access
  if (role === 'admin' || role === 'staff') {
    // Admin/staff should not be on guest pages or seller/driver dashboards
    if (isGuestPage || sellerRoutes || driverRoutes) {
      return navigateTo('/admin/dashboard')
    }
  }

  if (role === 'seller') {
    // Seller should not be on admin/driver pages or pure guest pages like landing
    if (adminRoutes || driverRoutes) {
      return navigateTo('/seller/dashboard')
    }
    // Allow sellers to browse customer pages (they might want to shop too)
  }

  if (role === 'driver') {
    if (adminRoutes || sellerRoutes) {
      return navigateTo('/driver/dashboard')
    }
  }

  if (role === 'customer') {
    if (adminRoutes || sellerRoutes || driverRoutes || staffRoutes) {
      return navigateTo('/')
    }
  }
})