/**
 * Trả về một instance với các method get/post/put/patch/delete
 * tự động gắn Authorization header từ cookie auth_token.
 */
function getToken(): string | null {
  if (typeof document === 'undefined') return null
  const match = document.cookie.match(/(?:^|;\s*)auth_token=([^;]*)/)
  return match ? decodeURIComponent(match[1]) : null
}

function buildHeaders(extra?: Record<string, string>): Record<string, string> {
  const token = getToken()
  return {
    Accept: 'application/json',
    'Content-Type': 'application/json',
    ...(token ? { Authorization: `Bearer ${token}` } : {}),
    ...extra,
  }
}

function req<T>(method: string, url: string, body?: unknown, params?: Record<string, unknown>, responseType?: 'json' | 'blob'): Promise<T> {
  const config = useRuntimeConfig()
  return $fetch<T>(`${config.public.apiBase}/api${url}`, {
    method,
    headers: buildHeaders(),
    body,
    params,
    responseType,
  })
}

export function getAuthInstance() {
  return {
    get:    <T>(url: string, params?: Record<string, unknown>) => req<T>('GET', url, undefined, params),
    post:   <T>(url: string, data?: unknown)                   => req<T>('POST', url, data),
    put:    <T>(url: string, data?: unknown)                   => req<T>('PUT', url, data),
    patch:  <T>(url: string, data?: unknown)                   => req<T>('PATCH', url, data),
    delete: <T>(url: string)                                   => req<T>('DELETE', url),
    getBlob: (url: string, params?: Record<string, unknown>)   => req<Blob>('GET', url, undefined, params, 'blob'),
  }
}
