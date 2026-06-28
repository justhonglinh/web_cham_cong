export function useApi() {
  const config = useRuntimeConfig()
  const authStore = useAuthStore()

  const apiBase = config.public.apiBase

  function getHeaders(): Record<string, string> {
    const headers: Record<string, string> = {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
    }
    if (authStore.token) {
      headers['Authorization'] = `Bearer ${authStore.token}`
    }
    return headers
  }

  async function get<T>(path: string, params?: Record<string, unknown>): Promise<T> {
    return $fetch<T>(`${apiBase}/api${path}`, {
      method: 'GET',
      headers: getHeaders(),
      params,
    })
  }

  async function post<T>(path: string, body?: unknown): Promise<T> {
    return $fetch<T>(`${apiBase}/api${path}`, {
      method: 'POST',
      headers: getHeaders(),
      body,
    })
  }

  async function put<T>(path: string, body?: unknown): Promise<T> {
    return $fetch<T>(`${apiBase}/api${path}`, {
      method: 'PUT',
      headers: getHeaders(),
      body,
    })
  }

  async function patch<T>(path: string, body?: unknown): Promise<T> {
    return $fetch<T>(`${apiBase}/api${path}`, {
      method: 'PATCH',
      headers: getHeaders(),
      body,
    })
  }

  async function del<T>(path: string): Promise<T> {
    return $fetch<T>(`${apiBase}/api${path}`, {
      method: 'DELETE',
      headers: getHeaders(),
    })
  }

  return { get, post, put, patch, del }
}
