export interface PaginatedResponse<T> {
  data: T[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export function usePagination(defaultPerPage = 10) {
  const currentPage = ref(1)
  const lastPage = ref(1)
  const total = ref(0)
  const perPage = ref(defaultPerPage)

  // Server-side: parse API response
  function setFromResponse<T>(res: PaginatedResponse<T> | T[]): T[] {
    if (Array.isArray(res)) {
      currentPage.value = 1
      lastPage.value = 1
      total.value = res.length
      return res
    }
    currentPage.value = res.current_page ?? 1
    lastPage.value = res.last_page ?? 1
    total.value = res.total ?? 0
    perPage.value = res.per_page ?? perPage.value
    return res.data ?? []
  }

  // Client-side: set total count and compute lastPage
  function setTotal(count: number) {
    total.value = count
    lastPage.value = Math.max(1, Math.ceil(count / perPage.value))
    if (currentPage.value > lastPage.value) currentPage.value = 1
  }

  // Client-side: slice an array for current page
  function paginateArray<T>(items: T[]): T[] {
    const start = (currentPage.value - 1) * perPage.value
    return items.slice(start, start + perPage.value)
  }

  function goToPage(page: number, fetchFn?: (page: number) => void) {
    if (page < 1 || page > lastPage.value) return
    currentPage.value = page
    fetchFn?.(page)
  }

  const visiblePages = computed(() => {
    const tot = lastPage.value
    const cur = currentPage.value
    if (tot <= 7) return Array.from({ length: tot }, (_, i) => i + 1)

    const pages: (number | '...')[] = []
    const start = Math.max(2, cur - 2)
    const end = Math.min(tot - 1, cur + 2)

    pages.push(1)
    if (start > 2) pages.push('...')
    for (let p = start; p <= end; p++) pages.push(p)
    if (end < tot - 1) pages.push('...')
    pages.push(tot)
    return pages
  })

  const summaryFrom = computed(() => total.value === 0 ? 0 : (currentPage.value - 1) * perPage.value + 1)
  const summaryTo = computed(() => Math.min(currentPage.value * perPage.value, total.value))

  return {
    currentPage, lastPage, total, perPage,
    setFromResponse, setTotal, paginateArray,
    goToPage, visiblePages, summaryFrom, summaryTo,
  }
}
