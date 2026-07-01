import { getAuthInstance } from '~/utils/api'
import type { WorkSummary } from '~/types/workSummary'

export type { WorkSummary }

export const workSummaryService = {
  getAll: async (params?: { month?: number; year?: number }) => {
    return await getAuthInstance().get<{ data: WorkSummary[] } | WorkSummary[]>('/work-summary/management', params)
  },

  export: async (params?: { month?: number; year?: number }) => {
    return await getAuthInstance().getBlob('/work-summary/export', params)
  },
}
