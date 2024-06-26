const defaultTheme = require('tailwindcss/defaultTheme')

module.exports = {
    content: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            backgroundImage: defaultTheme => ({
                'hero-pattern': "url('../../public/img/bg-landing.jpg')",
                'drl-screenshot': "url('../../public/img/bg-drl-screenshot.png')",
            }),

            colors: {
                'maroon-dream': '#7D2746',
            },

            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
        },
        opacity: {
            '0': '0',
            '10': '.1',
            '20': '.2',
            '25': '.25',
            '30': '.3',
            '40': '.4',
            '50': '.5',
            '60': '.6',
            '70': '.7',
            '75': '.75',
            '80': '.8',
            '90': '.9',
            '100': '1',
        }
    },
    variants: {
        extend: {
            outline: ['hover', 'active'],
            ringColor: ['hover', 'active'],
            ringOffsetColor: ['hover', 'active'],
            ringOffsetWidth: ['hover', 'active'],
            ringOpacity: ['hover', 'active'],
            ringWidth: ['hover', 'active'],
        }
    },
    plugins: [
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
