export const useAuthStore = defineStore("auth", () => {
  const user = ref<any>(null)
  const token = ref<string | null>(null)
  const role = ref<string | null>(null)
  const isLoggedIn = computed(() => !!token.value)

  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase

  const login = async (email: string, password: string) => {
    const res: any = await $fetch("/login", {
      baseURL,
      method: "POST",
      body: { email, password },
      headers: { Accept: "application/json" },
    })
    token.value = res.token
    user.value = res.user
    role.value = res.role
    if (import.meta.client) {
      localStorage.setItem("obra_token", res.token)
      localStorage.setItem("obra_user", JSON.stringify(res.user))
      localStorage.setItem("obra_role", res.role)
    }
    return res
  }

  const logout = async () => {
    const { post } = useApi()
    await post("/logout").catch(() => {})
    token.value = null
    user.value = null
    role.value = null
    if (import.meta.client) {
      localStorage.removeItem("obra_token")
      localStorage.removeItem("obra_user")
      localStorage.removeItem("obra_role")
    }
  }

  const loadFromStorage = () => {
    if (import.meta.client) {
      token.value = localStorage.getItem("obra_token")
      role.value = localStorage.getItem("obra_role")
      const stored = localStorage.getItem("obra_user")
      user.value = stored ? JSON.parse(stored) : null
    }
  }

  return { user, token, role, isLoggedIn, login, logout, loadFromStorage }
})