import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js', // Pastikan js juga masuk jika ada script dinamis
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Plus Jakarta Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Warna #0089ba yang kamu pilih beserta turunannya untuk efek hover/fokus
                primary: {
                    DEFAULT: '#0089ba',
                    50: '#e5f6fc',
                    100: '#ccecf9',
                    500: '#0089ba',
                    600: '#006e95', // Lebih gelap untuk hover
                    700: '#005370',
                }
            }
        },
    },

    plugins: [forms],
};
