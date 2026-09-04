import { prefersReducedMotion } from '../lib/prefs.js';

const STORAGE_KEY = 'theme';
const THEME_COLOR = { light: '#fafaf9', dark: '#080808' };

/**
 * Light/dark toggle. Tokens are light-dark() driven, so all this does is pin
 * `color-scheme` via [data-theme] on <html>, persist the choice, and keep the
 * button label + theme-color meta honest. With no stored choice the OS wins
 * (the pre-paint script in the layout mirrors this logic to avoid a flash).
 */
export function initThemeToggle() {
    const buttons = document.querySelectorAll('[data-theme-toggle]');
    if (buttons.length === 0) return;

    const root = document.documentElement;
    const osLight = matchMedia('(prefers-color-scheme: light)');
    const themeMeta = document.querySelector('meta[name="theme-color"]');

    const resolved = () => root.dataset.theme || (osLight.matches ? 'light' : 'dark');

    const sync = () => {
        const current = resolved();
        const next = current === 'light' ? 'dark' : 'light';
        buttons.forEach((button) => {
            button.setAttribute('aria-label', `Switch to ${next} theme`);
            button.setAttribute('title', `Switch to ${next} theme`);
        });
        themeMeta?.setAttribute('content', THEME_COLOR[current]);
    };

    const apply = (theme) => {
        const swap = () => {
            root.dataset.theme = theme;
            try {
                localStorage.setItem(STORAGE_KEY, theme);
            } catch {
                /* private mode / storage disabled — the choice just won't persist */
            }
            sync();
        };
        if (typeof document.startViewTransition === 'function' && !prefersReducedMotion) {
            document.startViewTransition(swap);
        } else {
            swap();
        }
    };

    buttons.forEach((button) => {
        button.addEventListener('click', () => apply(resolved() === 'light' ? 'dark' : 'light'));
    });

    // OS flips while no explicit pin is stored → follow it.
    osLight.addEventListener('change', sync);
    sync();
}
