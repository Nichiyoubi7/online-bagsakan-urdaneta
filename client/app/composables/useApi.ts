export const useApi = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase

  const getToken = () => {
    if (import.meta.client) {
      return localStorage.getItem("obra_token")
    }
    return null
  }

  const get = async (endpoint: string, params?: Record<string, any>) => {
    return await $fetch(endpoint, {
      baseURL,
      method: "GET",
      params,
      headers: {
        Authorization: `Bearer ${getToken()}`,
        Accept: "application/json",
      },
    })
  }

  const post = async (endpoint: string, body?: Record<string, any>) => {
    return await $fetch(endpoint, {
      baseURL,
      method: "POST",
      body,
      headers: {
        Authorization: `Bearer ${getToken()}`,
        Accept: "application/json",
      },
    })
  }

  const put = async (endpoint: string, body?: Record<string, any>) => {
    return await $fetch(endpoint, {
      baseURL,
      method: "PUT",
      body,
      headers: {
        Authorization: `Bearer ${getToken()}`,
        Accept: "application/json",
      },
    })
  }

  const destroy = async (endpoint: string) => {
    return await $fetch(endpoint, {
      baseURL,
      method: "DELETE",
      headers: {
        Authorization: `Bearer ${getToken()}`,
        Accept: "application/json",
      },
    })
  }

  return { get, post, put, destroy }
}
