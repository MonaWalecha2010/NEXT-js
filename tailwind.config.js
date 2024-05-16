const defaultTheme = require('tailwindcss/defaultTheme');
const { colors } = require('tailwindcss/defaultTheme')
/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    safelist: [
        {
            pattern: /./, // the "." means "everything"
        },
    ],
    theme: {
        extend: {           
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            fontSize: {
                xsm: '12px', 
                sm:  '14px'             
            },
            colors: {
                red: {
                    ...colors.red,
                    '100': '#FEE2E2',
                    '600': '#DC2626'
                },
                blue:{
                    ...colors.blue,
                    '50': '#EFF6FF',
                    '100': '#DBEAFE',
                    '300': '#1989E9',
                    '500': '#3B82F6'
                },
                green:{
                    ...colors.green,
                    '50': '#ECFDF5',
                    '500': '#10B981'
                },
                yellow:{
                    ...colors.yellow,
                    '100': '#FEF3C7',
                    '500': '#F59E0B'
                },
                gray:{
                    ...colors.gray,
                    'lightest': '#F8F8F8',
                    '50': '#F9FAFB',
                    '300': '#D1D5DB',
                    '400': '#9CA3AF',
                    '500': '#6B7280',
                    '600': '#4B5563'
                }
            
            },
            minWidth: {
                '16': '4rem',
                '24': '6rem',
                '40': '10rem',
                '44': '11rem'
            }
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
    ],
};
