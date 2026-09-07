import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                heading: ['Roboto', ...defaultTheme.fontFamily.sans],
                body: ['"Nunito Sans"', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f3f9fc',
                    100: '#e3f2f7',
                    200: '#b8e5f5',
                    300: '#82d1ed',
                    400: '#29c7ff',
                    500: '#00a7e3',
                    600: '#0089ba',
                    700: '#007199',
                    800: '#005e80',
                    900: '#004b66',
                    950: '#052b39',
                    DEFAULT: '#0089ba',
                },
                base: '#F2F2F2',
                surface: '#FFFFFF',
                'text-primary': '#171717',
                'text-secondary': '#737373',
            }
        },
    },

    plugins: [forms],
};
