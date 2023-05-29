import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import * as flowbite from 'flowbite/plugin'

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './node_modules/flowbite/**/*.js'
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                anzac: {
                '50': '#fdf9ef',
                '100': '#faf1da',
                '200': '#f3dfb5',
                '300': '#ebc886',
                '400': '#e2a854',
                '500': '#db9034',
                '600': '#cd7829',
                '700': '#aa5e24',
                '800': '#884b24',
                '900': '#6e3f20',
                '950': '#3b1e0f',
                },
                'ship-gray': {
                '50': '#f5f5f6',
                '100': '#e6e5e8',
                '200': '#d0cdd4',
                '300': '#afabb5',
                '400': '#878090',
                '500': '#6d6575',
                '600': '#5d5763',
                '700': '#4f4a54',
                '800': '#464149',
                '900': '#353237',
                '950': '#262428',
                },
                'river-bed': {
                '50': '#f4f6f7',
                '100': '#e2e8eb',
                '200': '#c9d4d8',
                '300': '#a3b5bd',
                '400': '#768e9a',
                '500': '#5b737f',
                '600': '#4e606c',
                '700': '#414e57',
                '800': '#3d464d',
                '900': '#363d43',
                '950': '#21262b',
                },
                'nutmeg': {
                '50': '#fbf7f1',
                '100': '#f6ebde',
                '200': '#ecd4bc',
                '300': '#e0b691',
                '400': '#d39264',
                '500': '#ca7745',
                '600': '#bc623a',
                '700': '#9c4d32',
                '800': '#773c2c',
                '900': '#663628',
                '950': '#361a14',
                },
                'kilamanjaro': {
                '50': '#fff4ec',
                '100': '#ffe6d3',
                '200': '#ffc9a6',
                '300': '#ffa36d',
                '400': '#ff7233',
                '500': '#ff4b0b',
                '600': '#f52f01',
                '700': '#cb1e03',
                '800': '#a11a0b',
                '900': '#81180d',
                '950': '#110201',
                },
                'neutral': {
                    ...defaultTheme.colors.neutral,
                    '750': '#333339',
                }
            }
        },
    },
    plugins: [forms],
};
