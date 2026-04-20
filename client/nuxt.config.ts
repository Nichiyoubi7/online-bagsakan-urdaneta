export default defineNuxtConfig({
  app: {
    head: {
      title: "OBRA",
      meta: [
        { name: "description", content: "Online Bagsakan Urdaneta - Fresh products from local sellers in Urdaneta City, Pangasinan." }
      ],
      link: [
        { rel: "manifest", href: "/manifest.webmanifest" }
      ]
    }
  },

  modules: [
    "@nuxt/eslint",
    "@nuxt/ui",
    "@pinia/nuxt",
    "@vite-pwa/nuxt",
  ],

  pwa: {
    registerType: "autoUpdate",
    manifest: {
      name: "OBRA - Online Bagsakan Urdaneta",
      short_name: "OBRA",
      description: "Online Bagsakan for Rural Produce and Animal Livestock — Urdaneta City, Pangasinan",
      theme_color: "#00C16A",
      background_color: "#ffffff",
      display: "standalone",
      orientation: "portrait",
      scope: "/",
      start_url: "/",
      icons: [
        {
          src: "/images/logo/OBRA_Logo.png",
          sizes: "192x192",
          type: "image/png",
        },
        {
          src: "/images/logo/OBRA_Logo.png",
          sizes: "512x512",
          type: "image/png",
        },
      ],
    },
    workbox: {
      navigateFallback: "/",
      globPatterns: ["**/*.{js,css,html,png,svg,ico}"],
    },
    devOptions: {
      enabled: false,
    },
  },

  components: [
    { path: "~/components/layout", pathPrefix: false },
    { path: "~/components/nav",    pathPrefix: false },
    { path: "~/components/shared", pathPrefix: false },
    { path: "~/components",        pathPrefix: false },
  ],

  runtimeConfig: {
    public: {
      apiBase: "https://api.obra-ur.xyz/api",
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