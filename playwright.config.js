import { defineConfig, devices } from '@playwright/test';

const baseURL = process.env.PLAYWRIGHT_BASE_URL || 'http://127.0.0.1:8000';

/** Shared browser defaults — keep on projects so device presets cannot drop them. */
const a11yStable = {
    colorScheme: 'dark',
    // Hero/magnetic transforms make axe color-contrast sample compositing layers.
    reducedMotion: 'reduce',
};

export default defineConfig({
    testDir: './tests/e2e',
    fullyParallel: true,
    forbidOnly: !!process.env.CI,
    retries: process.env.CI ? 1 : 0,
    workers: process.env.CI ? 2 : undefined,
    reporter: process.env.CI ? 'github' : 'list',
    use: {
        baseURL,
        trace: 'on-first-retry',
        ...a11yStable,
    },
    webServer: process.env.PLAYWRIGHT_SKIP_WEBSERVER
        ? undefined
        : {
              command: 'A11Y_FIXTURES=true php artisan serve --host=127.0.0.1 --port=8000',
              url: baseURL + '/up',
              reuseExistingServer: !process.env.CI,
              timeout: 120_000,
          },
    projects: [
        {
            name: 'chromium',
            use: { ...devices['Desktop Chrome'], ...a11yStable },
        },
        {
            name: 'mobile',
            use: { ...devices['Pixel 7'], ...a11yStable },
        },
    ],
});
