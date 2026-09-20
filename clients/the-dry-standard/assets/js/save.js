import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function saveTray() {
    const storageKey = "dry-standard-saved";
    const max = 40;

    const read = () => {
      try {
        const raw = JSON.parse(localStorage.getItem(storageKey) || "[]");
        if (!Array.isArray(raw)) {
          return [];
        }
        return raw
          .filter((item) => item && typeof item.slug === "string" && item.slug)
          .slice(0, max);
      } catch {
        return [];
      }
    };

    const write = (items) => {
      localStorage.setItem(storageKey, JSON.stringify(items.slice(0, max)));
    };

    const sync = () => {
      const saved = new Set(read().map((item) => item.slug));
      document.querySelectorAll("[data-save-toggle]").forEach((button) => {
        const on = saved.has(button.value);
        button.setAttribute("aria-pressed", String(on));
        button.textContent = on ? "Saved" : "Save";
      });
    };

    document.addEventListener("click", (event) => {
      const button = event.target.closest("[data-save-toggle]");
      if (!button) {
        return;
      }
      event.preventDefault();
      let items = read().filter((item) => item.slug !== button.value);
      const wasSaved = button.getAttribute("aria-pressed") === "true";
      if (!wasSaved) {
        items.unshift({
          slug: button.value,
          title: button.getAttribute("data-save-title") || button.value,
        });
      }
      write(items);
      sync();
      track("save_toggle", { slug: button.value, saved: !wasSaved, count: items.length });
    });

    sync();
  }
