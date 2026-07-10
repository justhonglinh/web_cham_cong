import { getAuthInstance } from '~/utils/api'
import type { PaginatedResponse } from '~/composables/usePagination'
import type { WorkSummary } from '~/types/workSummary'

export type { WorkSummary }

export const workSummaryService = {
  getAll: async (params?: { month?: number; year?: number; page?: number; per_page?: number; search?: string }) => {
    return await getAuthInstance().get<PaginatedResponse<WorkSummary> | WorkSummary[]>('/work-summary/management', params)
  },

  export: async (params?: { month?: number; year?: number }) => {
    return await getAuthInstance().getBlob('/work-summary/export', params)
  },
}
