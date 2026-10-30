import { prefersReducedMotion } from '../lib/prefs.js';

const STORAGE_KEY = 'theme';
const THEME_COLOR = { light: '#f4f6f8', dark: '#0d1520' };

export function resolvedTheme() {
    return document.documentElement.dataset.theme === 'light' ? 'light' : 'dark';
}

function syncThemeChrome() {
    const current = resolvedTheme();
    const next = current === 'light' ? 'dark' : 'light';
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.setAttribute('aria-label', `Switch to ${next} theme`);
        button.setAttribute('title', `Switch to ${next} theme`);
    });
    document
        .querySelector('meta[name="theme-color"]')
        ?.setAttribute('content', THEME_COLOR[current]);
    document.querySelector('meta[name="color-scheme"]')?.setAttribute('content', current);
}

export function toggleTheme() {
    const theme = resolvedTheme() === 'light' ? 'dark' : 'light';
    const swap = () => {
        document.documentElement.dataset.theme = theme;
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch {
            /* private mode / storage disabled — the choice just won't persist */
        }
        syncThemeChrome();
    };

    if (typeof document.startViewTransition === 'function' && !prefersReducedMotion) {
        document.startViewTransition(swap);
    } else {
        swap();
    }
}

/**
 * Light/dark toggle. Tokens are light-dark() driven, so all this does is pin
 * `color-scheme` via [data-theme] on <html>, persist the choice, and keep the
 * theme-color meta honest. With no stored choice the site stays dark (the
 * pre-paint script in the layout mirrors this to avoid a flash). Theme
 * switching lives on [data-theme-toggle] in the header.
 */
export function initThemeToggle() {
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', toggleTheme);
    });
    syncThemeChrome();
}
