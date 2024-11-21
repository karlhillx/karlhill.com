/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

import $ from 'jquery/dist/jquery';
window.jQuery = window.$ = $

/**
 * The axios HTTP library is used by a variety of first-party Laravel packages
 * like Inertia in order to make requests to the Laravel backend. This will
 * automatically handle sending the CSRF token via a header based on the
 * value of the "XSRF" token cookie sent with previous HTTP responses.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
