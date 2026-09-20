import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function compareTray() {
    const storageKey = "dry-standard-compare";
    const max = 4;
    const basePath = (() => {
      const link = document.querySelector('a[href*="/compare/"]');
      if (link) {
        try {
          const url = new URL(link.href, window.location.href);
          return url.pathname.endsWith("/") ? url.pathname : `${url.pathname}/`;
        } catch {
          /* fall through */
        }
      }
      const marker = "/clients/the-dry-standard/";
      const idx = window.location.pathname.indexOf(marker);
      if (idx >= 0) {
        return `${window.location.pathname.slice(0, idx + marker.length)}compare/`;
      }
      return "/compare/";
    })();

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

    let tray = document.querySelector("[data-compare-tray]");
    if (!tray) {
      tray = document.createElement("div");
      tray.className = "compare-tray";
      tray.dataset.compareTray = "";
      tray.hidden = true;
      tray.innerHTML = `
        <div class="compare-tray-inner">
          <p class="compare-tray-meta" data-compare-tray-meta></p>
          <ul class="compare-tray-list" data-compare-tray-list></ul>
          <a class="btn" data-compare-tray-go href="#">Open compare</a>
        </div>
      `;
      document.body.appendChild(tray);
    }

    const list = tray.querySelector("[data-compare-tray-list]");
    const meta = tray.querySelector("[data-compare-tray-meta]");
    const go = tray.querySelector("[data-compare-tray-go]");

    const syncChecks = (items) => {
      const selected = new Set(items.map((item) => item.slug));
      document.querySelectorAll("[data-compare-toggle]").forEach((input) => {
        input.checked = selected.has(input.value);
        input.disabled = !input.checked && selected.size >= max;
      });
    };

    const render = () => {
      const items = read();
      syncChecks(items);
      if (!items.length) {
        tray.classList.remove("is-open");
        tray.hidden = true;
        return;
      }

      tray.hidden = false;
      tray.classList.add("is-open");
      meta.textContent = `${items.length} of ${max} selected`;
      list.innerHTML = items.map((item) => `
        <li>
          <span>${item.title || item.slug}</span>
          <button type="button" data-compare-remove="${item.slug}" aria-label="Remove ${item.title || item.slug}">×</button>
        </li>
      `).join("");
      go.href = `${basePath}?slugs=${encodeURIComponent(items.map((item) => item.slug).join(","))}`;
      go.toggleAttribute("aria-disabled", items.length < 2);
      go.classList.toggle("is-disabled", items.length < 2);
    };

    document.addEventListener("change", (event) => {
      const input = event.target.closest("[data-compare-toggle]");
      if (!input) {
        return;
      }

      let items = read().filter((item) => item.slug !== input.value);
      if (input.checked) {
        if (items.length >= max) {
          input.checked = false;
          return;
        }
        items.push({
          slug: input.value,
          title: input.getAttribute("data-compare-title") || input.value,
        });
      }
      write(items);
      render();
      track("compare_toggle", { slug: input.value, selected: input.checked, count: items.length });
    });

    tray.addEventListener("click", (event) => {
      const button = event.target.closest("[data-compare-remove]");
      if (!button) {
        return;
      }
      const items = read().filter((item) => item.slug !== button.getAttribute("data-compare-remove"));
      write(items);
      render();
    });

    go.addEventListener("click", (event) => {
      if (read().length < 2) {
        event.preventDefault();
      }
    });

    render();
  }
