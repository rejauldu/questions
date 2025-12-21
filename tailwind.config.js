import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import colors from 'tailwindcss/colors'

export default {
    content: [
        // Optimized content paths
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            // --- Custom Colors ---
            colors: {
                'primary': colors.indigo,
                'secondary': colors.gray,
                'success': colors.green,
                'danger': colors.red,
                'warning': colors.yellow,
                'info': colors.sky,
                'main-indigo': colors.indigo,
                'accent-yellow': colors.yellow,
            },

            // --- Custom Spacing ---
            spacing: {
                '14': '3.5rem',  // 56px
                '18': '4.5rem',  // 72px
                '72': '18rem',
                '84': '21rem',
                '96': '24rem',
            },

            // --- Font Family ---
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        typography,
    ],
}