export default defineAppConfig({
  ui: {
    colors: {
      primary: 'blue',
      neutral: 'gray',
    },
    card: {
      slots: {
        root: 'rounded-xl shadow-xs',
      },
    },
    button: {
      slots: {
        base: 'rounded-lg font-semibold',
      },
    },
  },
})
