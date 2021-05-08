module.exports = {
    mode: 'jit',
    purge: {
        enabled: true,
        mode: 'all',
        preserveHtmlElements: false,
        content: [
            './vendor/laravel/jetstream/**/*.blade.php',
            './storage/framework/views/*.php',
            './resources/**/*.blade.php',
            './resources/**/*.js',
            './resources/**/*.vue',
        ],
    },
    darkMode: false, // or 'media' or 'class'
    theme: {
        fontFamily: {
            display: ['Inter', 'system-ui', 'sans-serif'],
            body: ['Inter', 'system-ui', 'sans-serif'],
            sans: ['Inter', 'system-ui', 'sans-serif'],
        },
    },
    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
        extend: {
            borderStyle: ['hover'],
        }
    },
    plugins: [
        require('autoprefixer'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/aspect-ratio'),
    ]
};
