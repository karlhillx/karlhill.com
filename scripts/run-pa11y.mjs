#!/usr/bin/env node
/**
 * Run pa11y-ci with a Chrome build pa11y's nested Puppeteer can launch.
 *
 * Why this exists:
 * - Top-level `puppeteer` (resume PDF) and pa11y-ci pin different Chrome builds.
 * - Cursor/sandbox often sets PUPPETEER_CACHE_DIR to an ephemeral path where
 *   Chrome-for-Testing frameworks fail to dlopen.
 *
 * Usage: node scripts/run-pa11y.mjs [--install-only]
 */
import { spawnSync } from 'node:child_process';
import { createRequire } from 'node:module';
import { existsSync, mkdtempSync, readFileSync, writeFileSync } from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const installOnly = process.argv.includes('--install-only');
const root = path.resolve(path.dirname(fileURLToPath(import.meta.url)), '..');
const require = createRequire(import.meta.url);

const stableCache = path.join(os.homedir(), '.cache', 'puppeteer');
const currentCache = process.env.PUPPETEER_CACHE_DIR ?? '';
if (!currentCache || currentCache.includes('cursor-sandbox-cache')) {
    process.env.PUPPETEER_CACHE_DIR = stableCache;
}

const pa11yCiDir = path.dirname(require.resolve('pa11y-ci/package.json'));
const puppeteerDir = path.dirname(
    require.resolve('puppeteer/package.json', { paths: [pa11yCiDir] }),
);
const puppeteer = require(require.resolve('puppeteer', { paths: [pa11yCiDir] }));

const systemChromeCandidates = [
    '/Applications/Google Chrome.app/Contents/MacOS/Google Chrome',
    '/usr/bin/google-chrome',
    '/usr/bin/google-chrome-stable',
    '/usr/bin/chromium',
    '/usr/bin/chromium-browser',
];

function bundledChromePath() {
    try {
        return puppeteer.executablePath();
    } catch {
        return '';
    }
}

function ensureBundledChrome() {
    let executable = bundledChromePath();
    if (executable && existsSync(executable)) {
        return executable;
    }

    console.log(
        `Installing Chrome for pa11y (cache: ${process.env.PUPPETEER_CACHE_DIR})…`,
    );
    const install = spawnSync(process.execPath, [path.join(puppeteerDir, 'install.mjs')], {
        cwd: puppeteerDir,
        env: process.env,
        stdio: 'inherit',
    });
    if (install.status !== 0) {
        process.exit(install.status ?? 1);
    }

    executable = bundledChromePath();
    if (!executable || !existsSync(executable)) {
        console.error(
            'pa11y Chrome is still missing after install.',
            executable ? `(expected ${executable})` : '',
        );
        process.exit(1);
    }

    return executable;
}

function resolveChrome() {
    // Prefer a real browser binary when present — avoids sandbox cache breakage.
    for (const candidate of systemChromeCandidates) {
        if (existsSync(candidate)) {
            return candidate;
        }
    }

    return ensureBundledChrome();
}

const executablePath = resolveChrome();
if (installOnly) {
    // Still ensure the bundled build exists for CI / Linux machines without Chrome.
    if (!systemChromeCandidates.includes(executablePath)) {
        console.log(`pa11y Chrome ready: ${executablePath}`);
    } else {
        ensureBundledChrome();
        console.log(`pa11y will use system Chrome: ${executablePath}`);
        console.log(`Bundled Chrome also ready: ${bundledChromePath()}`);
    }
    process.exit(0);
}

const baseConfig = JSON.parse(readFileSync(path.join(root, '.pa11yci.json'), 'utf8'));
baseConfig.defaults ??= {};
baseConfig.defaults.chromeLaunchConfig = {
    ...(baseConfig.defaults.chromeLaunchConfig ?? {}),
    executablePath,
};

const configDir = mkdtempSync(path.join(os.tmpdir(), 'pa11y-ci-'));
const configPath = path.join(configDir, 'config.json');
writeFileSync(configPath, `${JSON.stringify(baseConfig, null, 2)}\n`);

const bin = require.resolve('pa11y-ci/bin/pa11y-ci.js');
const result = spawnSync(process.execPath, [bin, '--config', configPath], {
    cwd: root,
    env: process.env,
    stdio: 'inherit',
});

process.exit(result.status ?? 1);
