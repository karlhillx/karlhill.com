/**
 * Axios HTTP library configuration
 * Used by Laravel packages like Inertia for making requests to the backend.
 * Automatically handles CSRF token via header based on "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

// Configure default headers
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Accept'] = 'application/json';

// Set up CSRF token handling
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
