const defaultTheme = require('tailwindcss/defaultTheme')
const colors = require('tailwindcss/colors')

module.exports = {
    mode: 'jit',
    purge: {
        enabled: true,
        mode: 'all',
        preserveHtmlElements: false,
        content: [
            './vendor/laravel/jetstream/**/*.blade.php',
            './storage/framework/views/*.php',
            './resources/views/**/*.blade.php',
        ]
    },
    theme: {
        extend: {
            backgroundImage: theme => ({
                'hero-pattern': "url('/img/bg-landing.jpg')",
                'drl-screenshot': "url('/img/bg-drl-screenshot.png')",
            }),

            colors: {
                cyan: colors.cyan,
                'maroon-dream': '#7D2746',
                orange: colors.orange,
                red: colors.red,
                rose: colors.rose
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
