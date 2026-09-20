export const body = document.body;
export const focusableSelector = 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])';

export const setLocked = (locked) => {
    body.classList.toggle("is-locked", locked);
  };

  export const analyticsEnabled = () => body.getAttribute("data-analytics") === "1";

  export const track = (name, detail = {}) => {
    if (!analyticsEnabled() || !name) {
      return;
    }

    const payload = { name, t: Date.now(), ...detail };
    window.dispatchEvent(new CustomEvent("dry-standard:event", {
      detail: payload,
    }));

    try {
      const key = "dry-standard-analytics";
      const raw = JSON.parse(localStorage.getItem(key) || "[]");
      const next = Array.isArray(raw) ? raw : [];
      next.push(payload);
      localStorage.setItem(key, JSON.stringify(next.slice(-200)));
    } catch {
      /* ignore quota / private mode */
    }

    if (typeof window.gtag === "function") {
      window.gtag("event", name, detail);
    }
  };

  export const trapFocus = (root, event) => {
    if (event.key !== "Tab" || !root) {
      return;
    }

    const items = [...root.querySelectorAll(focusableSelector)].filter((el) => !el.hidden && el.offsetParent !== null);
    if (!items.length) {
      return;
    }

    const first = items[0];
    const last = items[items.length - 1];

    if (event.shiftKey && document.activeElement === first) {
      event.preventDefault();
      last.focus();
    } else if (!event.shiftKey && document.activeElement === last) {
      event.preventDefault();
      first.focus();
    }
  };
