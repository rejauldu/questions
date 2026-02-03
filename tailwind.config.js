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

    safelist: [
        'col-span-1',
        'col-span-2',
        'col-span-3',
        'col-span-4',

        // built-in list styles
        'list-none',
        'list-disc',
        'list-decimal',

        // arbitrary list-style-type values
        'list-[circle]',
        'list-[lower-roman]',
        'list-[upper-roman]',
        'list-[lower-alpha]',
        'list-[upper-alpha]',

        // position (commonly needed)
        'list-inside',
        'list-outside',
        // common spacing helpers for lists
        'pl-1',
        'pl-2',
        'pl-3',
        'pl-4',
        'pl-5',
        'pl-6',
        'ml-1',
        'ml-2',
        'ml-3',
        'ml-4',
        'ml-5',
        'ml-6',
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