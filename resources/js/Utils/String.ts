export const toLocaleString = (input: any) => {
  if (!input) return 0
  return input.toLocaleString('sv-SE')
}

export const toRangeString = (input: any) => {
  if (!input) return 0
  return input.toLocaleString('sv-SE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}
