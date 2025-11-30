import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'
import colors from 'tailwindcss/colors' // Import to easily reference default colors

export default {
    content: [
        // front
        "./resources/views/**/*.blade.php",
        "./resources/js/app.js",
        // chatbot
        "./resources/js/chatbot.js",
        "./resources/js/Pages/Chatbot/**/*.vue",
        "./resources/js/Pages/Auth/**/*.vue",
        "./resources/js/Components/**/*.vue",
        "./resources/js/Layouts/**/*.vue",
        // dashboard
        "./resources/js/dashboard.js",
        "./resources/js/Pages/**/*.vue",
    ],

    theme: {
        extend: {
            // --- Custom Colors ---
            colors: {
                // Base colors from the design context
                'primary': colors.indigo,
                'secondary': colors.gray,
                
                // Functional colors
                'success': colors.green,
                'danger': colors.red,
                'warning': colors.yellow,
                'info': colors.sky,

                // Specific named colors (use these in place of 'indigo' and 'yellow' if desired)
                'main-indigo': colors.indigo,
                'accent-yellow': colors.yellow,
            },

            // --- Custom Spacing ---
            // Adds small, consistent steps for padding/margin beyond default.
            spacing: {
                '14': '3.5rem',  // 56px
                '18': '4.5rem',  // 72px
                '72': '18rem',
                '84': '21rem',
                '96': '24rem',
            },

            // --- Custom Shadows ---
            // Useful for consistent button/card elevation.
            boxShadow: {
                '3xl': '0 35px 60px -15px rgba(0, 0, 0, 0.3)',
                'inner-sm': 'inset 0 1px 3px 0 rgba(0, 0, 0, 0.06)',
            },

            // --- Font Family ---
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],

    // These classes are explicitly added so Tailwind doesn't tree-shake them if they aren't directly in the content files.
    safelist: [
        "fixed",
        "left-0",
        "top-0",
        "z-50",
        "w-64",
        "h-full",
        "shadow-lg",
        "hidden",
        "w-1/4",
        "max-w-[20rem]",
        "w-16",
    ],
}