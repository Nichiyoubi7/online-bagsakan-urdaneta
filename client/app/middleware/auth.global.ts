export default defineNuxtRouteMiddleware((to) => {
  if (!import.meta.client) return

  const authStore = useAuthStore()
  authStore.loadFromStorage()

  const role = authStore.role
  const isLoggedIn = authStore.isLoggedIn
  const path = to.path

  const isAdminRoute    = path.startsWith('/admin')
  const isSellerRoute   = path.startsWith('/seller') && !path.startsWith('/seller-store')
  const isDriverRoute   = path.startsWith('/driver')
  const isStaffRoute    = path.startsWith('/staff')
  const isCustomerRoute = path.startsWith('/customer')
  const isPublicRoute   = !isAdminRoute && !isSellerRoute && !isDriverRoute && !isStaffRoute && !isCustomerRoute

  // Not logged in — block dashboard routes only
  if (!isLoggedIn) {
    if (isAdminRoute || isSellerRoute || isDriverRoute || isStaffRoute) {
      return navigateTo('/')
    }
    return
  }

  // ADMIN or STAFF — only block non-admin routes, allow public browsing pages
  if (role === 'admin' || role === 'staff') {
    if (isSellerRoute || isDriverRoute) {
      return navigateTo('/admin/dashboard')
    }
    if (isCustomerRoute) {
      return navigateTo('/admin/dashboard')
    }
    if (path === '/') {
      return navigateTo('/admin/dashboard')
    }
  }

  // SELLER — allow browsing public pages and seller-store pages
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