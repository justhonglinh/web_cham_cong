/** 'yyyy-mm-dd' or ISO string → 'dd/mm/yyyy', returns '—' for empty */
export function formatDate(dateStr: string | null | undefined): string {
  if (!dateStr) return '—'
  const isoMatch = dateStr.match(/^(\d{4})-(\d{2})-(\d{2})/)
  if (isoMatch) return `${isoMatch[3]}/${isoMatch[2]}/${isoMatch[1]}`
  return dateStr
}

/** 'HH:MM:SS' or ISO string → 'HH:MM', returns '--:--' for null */
export function formatTime(timeStr: string | null | undefined): string {
  if (!timeStr) return '--:--'
  const isoMatch = timeStr.match(/^\d{4}-\d{2}-\d{2}[T ](\d{2}):(\d{2})/)
  if (isoMatch) return `${isoMatch[1]}:${isoMatch[2]}`
  const parts = timeStr.split(':')
  if (parts.length >= 2) return `${parts[0]}:${parts[1]}`
  const d = new Date(timeStr)
  return isNaN(d.getTime()) ? '--:--' : d.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
}
