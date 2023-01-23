/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'class',
  content: [
    'templates/**/*.html.twig',
    'assets/js/**/*.js',
    'assets/js/**/*.jsx',
  ],
  theme: {
    extend: {
      colors: {
        'main-white' : '#f0edee',
        'main-black' : '#08080c',
        'main-blue' : '#106b9f',
        'main-green' : '#7fb353',
        'main-orange' : '#ef8354',
        'main-red' : '#d2384d',
        'main-light-blue' : '#8ecae6',
        'admin-blue' : '#023047',
      }
    },
  },
  plugins: [],
}