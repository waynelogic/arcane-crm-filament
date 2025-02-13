import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Google Sans', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'big': 'rgba(0, 0, 0, 0.2) 0px 12px 28px 0px, rgba(0, 0, 0, 0.1) 0px 2px 4px 0px, rgba(255, 255, 255, 0.05) 0px 0px 0px 1px inset'
            },
            colors: {
                primary: {
                    '50': '#f2f5fb',
                    '100': '#e7ecf8',
                    '200': '#d4dcf1',
                    '300': '#bac6e7',
                    '400': '#9da8dc',
                    '500': '#858ccf',
                    '600': '#6e70c0',
                    '700': '#5b5ba7',
                    '800': '#4b4c88',
                    '900': '#42446d',
                    '950': '#262640',
                    DEFAULT: '#6e70c0',
                },
            }
        },
        container: {
            center: true,
        },
    },

    plugins: [forms],
};
