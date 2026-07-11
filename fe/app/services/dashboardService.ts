import { getAuthInstance } from '~/utils/api'
import type { DashboardData } from '~/types/dashboard'

export type { DashboardData }

export const dashboardService = {
  getManagerDashboard: async () => {
    return await getAuthInstance().get<DashboardData>('/dashboard')
  },
}
