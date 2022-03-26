module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      variants: {
        opacity: ({ after }) => after(['disabled'])
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
    // ...
  ],
}
