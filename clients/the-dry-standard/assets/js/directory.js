import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function directory() {
    const root = document.querySelector("[data-directory]");
    const input = root?.querySelector("[data-directory-q]");
    const rows = [...document.querySelectorAll(".directory-row")];
    const empty = document.querySelector("[data-directory-empty]");
    const count = document.querySelector("[data-directory-count]");

    if (!root || !input || !rows.length) {
      return;
    }

    const apply = () => {
      const needle = input.value.trim().toLowerCase();
      let visible = 0;

      rows.forEach((row) => {
        const show = !needle || (row.getAttribute("data-search") || "").includes(needle);
        row.hidden = !show;
        if (show) {
          visible += 1;
        }
      });

      document.querySelectorAll("[data-directory-group]").forEach((group) => {
        group.hidden = ![...group.querySelectorAll(".directory-row")].some((row) => !row.hidden);
      });

      document.querySelectorAll(".letter-nav a").forEach((link) => {
        const id = (link.getAttribute("href") || "").replace("#", "");
        const group = id ? document.getElementById(id) : null;
        link.hidden = !group || group.hidden;
      });

      if (count) {
        count.textContent = needle
          ? `${visible} of ${rows.length}`
          : `${rows.length} ${rows.length === 1 ? "entry" : "entries"}`;
      }

      if (empty) {
        empty.hidden = visible > 0;
      }
    };

    const syncUrl = () => {
      const next = new URL(window.location.href);
      const value = input.value.trim();

      if (value) {
        next.searchParams.set("q", value);
      } else {
        next.searchParams.delete("q");
      }

      const path = `${next.pathname}${next.search}${next.hash}`;
      const current = `${window.location.pathname}${window.location.search}${window.location.hash}`;

      if (path !== current) {
        history.replaceState({}, "", path);
      }
    };

    input.addEventListener("input", () => {
      apply();
      syncUrl();
    });
    apply();
  }
