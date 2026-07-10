interface ApiOptions {
  success?: string
  silent?: boolean    // bỏ qua cả success lẫn error toast
  showError?: boolean // bắt buộc hiện error toast (dùng cho GET nếu cần)
}

function extractErrorMessage(err: unknown): string {
  const e = err as { data?: { message?: string; errors?: Record<string, string[]> }; statusCode?: number }
  if (e?.data?.errors) {
    const first = Object.values(e.data.errors)[0]
    return Array.isArray(first) ? first[0] : String(first)
  }
  if (e?.data?.message) return e.data.message
  if (e?.statusCode === 401) return 'Phiên đăng nhập hết hạn.'
  if (e?.statusCode === 403) return 'Bạn không có quyền thực hiện thao tác này.'
  if (e?.statusCode === 404) return 'Không tìm thấy dữ liệu.'
  if (e?.statusCode === 422) return 'Dữ liệu không hợp lệ.'
  if (e?.statusCode === 500) return 'Lỗi máy chủ. Vui lòng thử lại sau.'
  return 'Đã có lỗi xảy ra. Vui lòng thử lại.'
}

export function useApi() {
  const authStore = useAuthStore()
  const toast = useAppToast()
  const config = useRuntimeConfig()

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

  async function request<T>(
    method: string,
    path: string,
    options?: ApiOptions & { body?: unknown; params?: Record<string, unknown> },
  ): Promise<T> {
    const isMutation = ['POST', 'PUT', 'PATCH', 'DELETE'].includes(method.toUpperCase())
    try {
      const result = await $fetch<T>(`${config.public.apiBase}/api${path}`, {
        method,
        headers: getHeaders(),
        body: options?.body,
        params: options?.params,
      })
      if (!options?.silent && options?.success) {
        toast.success(options.success)
      }
      return result
    } catch (err) {
      // GET errors: page tự xử lý inline — chỉ auto-toast cho mutations
      if (!options?.silent && (isMutation || options?.showError)) {
        toast.error(extractErrorMessage(err))
      }
      throw err
    }
  }

  async function get<T>(path: string, params?: Record<string, unknown>, options?: ApiOptions): Promise<T> {
    return request<T>('GET', path, { ...options, params })
  }

  async function post<T>(path: string, body?: unknown, options?: ApiOptions): Promise<T> {
    return request<T>('POST', path, { ...options, body })
  }

  async function put<T>(path: string, body?: unknown, options?: ApiOptions): Promise<T> {
    return request<T>('PUT', path, { ...options, body })
  }

  async function patch<T>(path: string, body?: unknown, options?: ApiOptions): Promise<T> {
    return request<T>('PATCH', path, { ...options, body })
  }

  async function del<T>(path: string, options?: ApiOptions): Promise<T> {
    return request<T>('DELETE', path, options)
  }

  return { get, post, put, patch, del }
}
