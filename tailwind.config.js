import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import colors from 'tailwindcss/colors'

/** @type {import("tailwindcss").Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue'
  ],

  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', ...defaultTheme.fontFamily.sans]
      },
      colors: {
        primary: colors.green,
        danger: colors.red
      }
    }
  },

  plugins: [forms]
}
