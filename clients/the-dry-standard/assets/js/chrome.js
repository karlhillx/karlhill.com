import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function chrome() {
    const header = document.querySelector("[data-header]");
    const nav = document.querySelector("[data-nav]");
    const toggle = document.querySelector("[data-nav-toggle]");
    const backdrop = document.querySelector("[data-nav-backdrop]");
    const headerQuery = document.querySelector("[data-header-q]");
    const searchToggle = document.querySelector("[data-search-toggle]");
    const searchPanel = document.querySelector("[data-search-panel]");

    if (header) {
      const onScroll = () => {
        header.classList.toggle("is-scrolled", window.scrollY > 8);
      };

      onScroll();
      window.addEventListener("scroll", onScroll, { passive: true });
    }

    if (headerQuery) {
      const query = new URLSearchParams(window.location.search).get("q");

      if (query) {
        headerQuery.value = query;
      }
    }

    const setSearchOpen = (open) => {
      searchToggle?.setAttribute("aria-expanded", String(open));
      searchPanel?.classList.toggle("is-open", open);
      if (open) {
        headerQuery?.focus();
      }
    };

    searchToggle?.addEventListener("click", () => {
      setSearchOpen(searchToggle.getAttribute("aria-expanded") !== "true");
    });

    if (!toggle || !nav) {
      return;
    }

    const setOpen = (open) => {
      toggle.setAttribute("aria-expanded", String(open));
      nav.classList.toggle("is-open", open);
      if (backdrop) {
        backdrop.hidden = !open;
        backdrop.inert = !open;
        backdrop.classList.toggle("is-visible", open);
      }
      setLocked(open || Boolean(document.querySelector("[data-archive-filters].is-open")));
      if (open) {
        nav.querySelector(focusableSelector)?.focus();
      } else {
        toggle.focus();
      }
    };

    toggle.addEventListener("click", () => {
      setOpen(toggle.getAttribute("aria-expanded") !== "true");
    });

    backdrop?.addEventListener("click", () => setOpen(false));

    nav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => setOpen(false));
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        setOpen(false);
        setSearchOpen(false);
      }
      if (nav.classList.contains("is-open")) {
        trapFocus(nav, event);
      }
    });
  }
