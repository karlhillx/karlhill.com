import Alpine from 'alpinejs'
import darkMode from './darkMode'
import './github-skills.js'

import '@fortawesome/fontawesome-free/css/fontawesome.css';
import '@fortawesome/fontawesome-free/css/solid.css';
import '@fortawesome/fontawesome-free/css/regular.css';
import '@fortawesome/fontawesome-free/css/brands.css';


// Make Alpine available globally
window.Alpine = Alpine

// Initialize theme handling after Alpine.js starts
document.addEventListener('DOMContentLoaded', () => {
    Alpine.data('darkMode', darkMode)
    Alpine.start()

    // Create a theme toggle function
    window.toggleTheme = function() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark')
            localStorage.theme = 'light'
        } else {
            document.documentElement.classList.add('dark')
            localStorage.theme = 'dark'
        }
    }

    // Set initial theme based on preference
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark')
    } else {
        document.documentElement.classList.remove('dark')
    }

    // Add event listener for dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
        });
    }
})

// Handle page visibility changes
document.addEventListener('visibilitychange', () => {
    if (document.visibilityState === 'visible') {
        // Refresh content or reconnect as needed
    }
});

