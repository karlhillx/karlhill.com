import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function archive() {
    const root = document.querySelector("[data-archive]");
    const form = root?.querySelector("[data-archive-filters]");

    if (!root || !(form instanceof HTMLFormElement)) {
      return;
    }

    const queryInput = root.querySelector("[data-archive-q]");
    const headerQuery = document.querySelector("[data-header-q]");
    const sortSelect = root.querySelector("[data-archive-sort]");
    const filterToggle = root.querySelector("[data-filter-toggle]");
    const filterBackdrop = root.querySelector("[data-filter-backdrop]");
    const lockedCategory = root.getAttribute("data-locked-category") || "";
    const lockedStyle = root.getAttribute("data-locked-style") || "";
    let searchTimer = 0;

    const selectedValues = (name) =>
      [...root.querySelectorAll(`[data-archive-${name}]:checked`)].map((input) => input.value);

    const hrefFromState = () => {
      const next = new URLSearchParams();
      const query = (queryInput?.value || headerQuery?.value || "").trim();

      if (query) {
        next.set("q", query);
      }

      ["brand", "abv", "category", "production", "method", "style", "country"].forEach((key) => {
        if (key === "category" && lockedCategory) {
          return;
        }
        if (key === "style" && lockedStyle) {
          return;
        }

        const values = selectedValues(key);

        if (values.length) {
          next.set(key, values.join(","));
        }
      });

      if (sortSelect?.value && sortSelect.value !== "newest") {
        next.set("sort", sortSelect.value);
      }

      const qs = next.toString();

      return qs ? `${window.location.pathname}?${qs}` : window.location.pathname;
    };

    const replaceArchive = (html) => {
      const parsed = document.createElement("div");
      parsed.innerHTML = html.trim();
      const next = parsed.querySelector("[data-archive]") || parsed.firstElementChild;
      if (!next) {
        window.location.assign(hrefFromState());
        return;
      }
      root.replaceWith(next);
      archive();
    };

    const fragmentUrlFor = (href) =>
      href.includes("?") ? `${href}&fragment=archive` : `${href}?fragment=archive`;

    const swapFrom = (href, push) => {
      root.setAttribute("aria-busy", "true");

      const swap = () =>
        fetch(fragmentUrlFor(href), { headers: { Accept: "text/html" } })
          .then((response) => {
            if (!response.ok) {
              throw new Error("filter");
            }
            return response.text();
          })
          .then((html) => {
            if (push) {
              history.pushState({}, "", href);
            }
            replaceArchive(html);
          })
          .catch(() => window.location.assign(href))
          .finally(() => root.removeAttribute("aria-busy"));

      if (document.startViewTransition) {
        document.startViewTransition(swap);
        return;
      }

      swap();
    };

    const navigate = () => {
      const next = hrefFromState();
      const current = `${window.location.pathname}${window.location.search}`;

      if (next === current) {
        return;
      }

      swapFrom(next, true);
    };

    const setFiltersOpen = (open) => {
      form.classList.toggle("is-open", open);
      filterToggle?.setAttribute("aria-expanded", String(open));
      if (filterBackdrop) {
        filterBackdrop.hidden = !open;
        filterBackdrop.inert = !open;
        filterBackdrop.classList.toggle("is-visible", open);
      }
      setLocked(open);
      if (open) {
        form.querySelector(focusableSelector)?.focus();
      }
    };

    root.querySelectorAll("[data-facet-find]").forEach((input) => {
      input.addEventListener("input", () => {
        const needle = input.value.trim().toLowerCase();
        const group = input.closest("[data-facet]");

        group?.querySelectorAll(".facet-option").forEach((option) => {
          const label = option.getAttribute("data-facet-label") || "";
          option.classList.toggle("is-filtered", Boolean(needle) && !label.includes(needle));
        });
      });
    });

    root.querySelectorAll("[data-facet-toggle]").forEach((button) => {
      button.addEventListener("click", () => {
        const group = button.closest("[data-facet]");
        const open = !group?.classList.contains("is-open");
        group?.classList.toggle("is-open", open);
        button.setAttribute("aria-expanded", String(open));
      });
    });

    root.addEventListener("change", (event) => {
      if (event.target.matches("[data-facet-find], [data-archive-q]")) {
        return;
      }

      navigate();
    });

    queryInput?.addEventListener("input", () => {
      window.clearTimeout(searchTimer);
      searchTimer = window.setTimeout(navigate, 320);
    });

    headerQuery?.addEventListener("change", () => {
      if (queryInput) {
        queryInput.value = headerQuery.value;
      }
    });

    form.addEventListener("submit", (event) => {
      event.preventDefault();
      setFiltersOpen(false);
      navigate();
    });

    filterToggle?.addEventListener("click", () => {
      setFiltersOpen(!form.classList.contains("is-open"));
    });
    filterBackdrop?.addEventListener("click", () => setFiltersOpen(false));
    root.querySelectorAll("[data-filter-close]").forEach((button) => {
      button.addEventListener("click", () => setFiltersOpen(false));
    });

    document.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        setFiltersOpen(false);
      }
      if (form.classList.contains("is-open")) {
        trapFocus(form, event);
      }
    });

    root._swapFromLocation = () => {
      const href = `${window.location.pathname}${window.location.search}`;
      swapFrom(href, false);
    };
  }
