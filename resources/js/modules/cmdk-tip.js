import { prefersFinePointer, prefersReducedMotion } from '../lib/prefs.js';

const CMDK_TIP_KEY = 'karlhill.cmdk-tip.dismissed';

export function initCmdkTip() {
    const cmdkTip = document.getElementById('cmdk-tip');
    if (!cmdkTip || !prefersFinePointer || prefersReducedMotion) return;

    try {
        if (window.localStorage.getItem(CMDK_TIP_KEY) === '1') return;
    } catch {
        // private mode / blocked storage — still show the tip
    }

    cmdkTip.hidden = false;
    window.setTimeout(() => cmdkTip.classList.add('is-visible'), 1400);

    const dismissCmdkTip = () => {
        cmdkTip.classList.remove('is-visible');
        try {
            window.localStorage.setItem(CMDK_TIP_KEY, '1');
        } catch {
            // private mode / blocked storage
        }
        window.setTimeout(() => {
            cmdkTip.hidden = true;
        }, 280);
    };

    cmdkTip.querySelector('[data-cmdk-tip-dismiss]')?.addEventListener('click', dismissCmdkTip);
    document.getElementById('command-palette')?.addEventListener('toggle', (e) => {
        if (e.newState === 'open') dismissCmdkTip();
    });
    window.setTimeout(dismissCmdkTip, 9000);
}
