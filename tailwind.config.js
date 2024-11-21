export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    future: {
        hoverOnlyWhenSupported: true
    },
    theme: {
        extend: {
            colors: {
                'maroon-dream': '#7D2746', // Custom dark mode color
                'light-gray': '#d3d3d3',   // Custom light mode color
            },
        },
    },
}
