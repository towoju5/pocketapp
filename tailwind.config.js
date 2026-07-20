import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        'node_modules/preline/dist/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'lightHouse': '#20273f',
                'lightHouse-text': '#8ea5c0',

                // Consolidated brand tokens — matches the flat-navy palette
                // used across the customer-facing trading app exactly, so
                // admin shares the same design language instead of its own
                // separate glassmorphism theme.
                brand: {
                    blue: '#4f8ef7',
                    'blue-hover': '#3f7de6',
                    navy: '#171e33',
                    deep: '#12182a',
                    emerald: '#16c087',
                    amber: '#d97706',
                    danger: '#f4534a',
                },
                glass: {
                    surface: '#171e33',
                    'surface-light': '#1c243c',
                    border: '#2a3350',
                },
            },
            fontFamily: {
                display: ['"Plus Jakarta Sans"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            backdropBlur: {
                xs: '2px',
            },
        },
    },

    plugins: [forms, require('preline/plugin'),],
};
