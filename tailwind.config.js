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

                // Consolidated brand tokens (replaces the reference app's
                // three competing palettes: auth split-screen, dark "glass"
                // app interior, and admin's amber accent).
                brand: {
                    blue: '#2563eb',
                    'blue-hover': '#1d4ed8',
                    navy: '#0f172a',
                    deep: '#020617',
                    emerald: '#10b981',
                    amber: '#f59e0b',
                    danger: '#ef4444',
                },
                glass: {
                    surface: 'rgba(15, 23, 42, 0.7)',
                    'surface-light': 'rgba(255, 255, 255, 0.03)',
                    border: 'rgba(255, 255, 255, 0.08)',
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
