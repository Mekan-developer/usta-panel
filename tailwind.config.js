import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                accent: 'rgb(124 108 246 / <alpha-value>)',
                warning: '#f4b93d',
                page: { DEFAULT: '#f3f4f8', dark: '#0f1320' },
                shell: { DEFAULT: '#ffffff', dark: '#151a2b' },
                card: { DEFAULT: '#ffffff', dark: '#1b2135' },
                field: { DEFAULT: '#f3f4f8', dark: '#242b42' },
                ink: { DEFAULT: '#171a24', dark: '#f1f2f6' },
                subtext: { DEFAULT: 'rgba(23,26,36,.55)', dark: 'rgba(241,242,246,.55)' },
                fadetext: { DEFAULT: 'rgba(23,26,36,.4)', dark: 'rgba(241,242,246,.38)' },
                edge: { DEFAULT: 'rgba(15,19,32,.08)', dark: 'rgba(255,255,255,.07)' },
                fieldedge: { DEFAULT: 'rgba(15,19,32,.1)', dark: 'rgba(255,255,255,.09)' },
            },
        },
    },

    plugins: [forms],
};
