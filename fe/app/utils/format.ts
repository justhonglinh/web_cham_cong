/** 'yyyy-mm-dd' → 'dd/mm/yyyy', returns '—' for empty */
export function formatDate(dateStr: string | null | undefined): string {
  if (!dateStr) return '—'
  const parts = dateStr.split('-')
  if (parts.length === 3) return `${parts[2]}/${parts[1]}/${parts[0]}`
  return dateStr
}

/** 'HH:MM:SS' or ISO string → 'HH:MM', returns '--:--' for null */
export function formatTime(timeStr: string | null | undefined): string {
  if (!timeStr) return '--:--'
  const parts = timeStr.split(':')
  if (parts.length >= 2) return `${parts[0]}:${parts[1]}`
  const d = new Date(timeStr)
  return isNaN(d.getTime()) ? '--:--' : d.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
}
