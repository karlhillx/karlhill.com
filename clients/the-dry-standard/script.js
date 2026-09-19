(() => {
  const body = document.body;

  const setLocked = (locked) => {
    body.classList.toggle("is-locked", locked);
  };

  const chrome = () => {
    const header = document.querySelector("[data-header]");
    const nav = document.querySelector("[data-nav]");
    const toggle = document.querySelector("[data-nav-toggle]");
    const backdrop = document.querySelector("[data-nav-backdrop]");
    const headerQuery = document.querySelector("[data-header-q]");

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

    if (!toggle || !nav) {
      return;
    }

    const setOpen = (open) => {
      toggle.setAttribute("aria-expanded", String(open));
      nav.classList.toggle("is-open", open);
      if (backdrop) {
        backdrop.hidden = !open;
        backdrop.classList.toggle("is-visible", open);
      }
      setLocked(open || Boolean(document.querySelector("[data-archive-filters].is-open")));
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
      }
    });

    header?.addEventListener("keydown", (event) => {
      if (event.key === "Escape") {
        setOpen(false);
      }
    });
  };

  const directory = () => {
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

    input.addEventListener("input", apply);
    apply();
  };

  const archive = () => {
    const root = document.querySelector("[data-archive]");
    const form = root?.querySelector("[data-archive-filters]");

    if (!root || !(form instanceof HTMLFormElement)) {
      return;
    }

    const queryInput = root.querySelector("[data-archive-q]");
    const sortSelect = root.querySelector("[data-archive-sort]");
    const filterToggle = root.querySelector("[data-filter-toggle]");
    const filterBackdrop = root.querySelector("[data-filter-backdrop]");
    const lockedCategory = root.getAttribute("data-locked-category") || "";
    let searchTimer = 0;

    const selectedValues = (name) =>
      [...root.querySelectorAll(`[data-archive-${name}]:checked`)].map((input) => input.value);

    const hrefFromState = () => {
      const next = new URLSearchParams();
      const query = queryInput?.value.trim() || "";

      if (query) {
        next.set("q", query);
      }

      ["brand", "abv", "category", "production", "method"].forEach((key) => {
        if (key === "category" && lockedCategory) {
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

    const navigate = () => {
      const next = hrefFromState();
      const current = `${window.location.pathname}${window.location.search}`;

      if (next !== current) {
        window.location.assign(next);
      }
    };

    const setFiltersOpen = (open) => {
      form.classList.toggle("is-open", open);
      filterToggle?.setAttribute("aria-expanded", String(open));
      if (filterBackdrop) {
        filterBackdrop.hidden = !open;
        filterBackdrop.classList.toggle("is-visible", open);
      }
      setLocked(open);
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
    });
  };

  chrome();
  directory();
  archive();
})();
