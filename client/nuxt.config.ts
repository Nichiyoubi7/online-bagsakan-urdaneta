export default defineNuxtConfig({
  app: {
    head: {
      title: "OBRA",
      meta: [
        { name: "description", content: "Online Bagsakan Urdaneta - Fresh products from local sellers in Urdaneta City, Pangasinan." }
      ]
    }
  },
  modules: [
    "@nuxt/eslint",
    "@nuxt/ui",
    "@pinia/nuxt",
  ],
  components: [
    { path: "~/components/layout", pathPrefix: false },
    { path: "~/components/nav",    pathPrefix: false },
    { path: "~/components/shared", pathPrefix: false },
    { path: "~/components",        pathPrefix: false },
  ],
  runtimeConfig: {
    public: {
      apiBase: "https://online-bagsakan-urdaneta-production.up.railway.app/api",
    }
  },
  vite: {
    server: {
      allowedHosts: ["nonconnectively-incised-neal.ngrok-free.dev"]
    }
  },
  devtools: { enabled: true },
  css: ["~/assets/css/main.css"],
  routeRules: {
    "/": { prerender: true }
  },
  compatibilityDate: "2025-01-15",
  eslint: {
    config: {
      stylistic: {
        commaDangle: "never",
        braceStyle: "1tbs"
      }
    }
  }
})
