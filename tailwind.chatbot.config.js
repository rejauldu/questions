import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/js/Pages/Chatbot/**/*.vue",
        "./resources/js/Pages/Auth/**/*.vue",
        "./resources/js/Components/**/*.vue",
        "./resources/js/Layouts/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],

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
};