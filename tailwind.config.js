/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'maroon-dream': '#18181b', // Custom color from your existing classes
      }
    }
  },
    plugins:[]
}
