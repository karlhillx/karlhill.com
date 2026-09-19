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
    const year = document.querySelector("[data-year]");
    const headerQuery = document.querySelector("[data-header-q]");

    if (year) {
      year.textContent = String(new Date().getFullYear());
    }

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
    const grid = document.querySelector("[data-review-grid]");

    if (!root || !grid) {
      return;
    }

    const queryInput = root.querySelector("[data-archive-q]");
    const sortSelect = root.querySelector("[data-archive-sort]");
    const countNodes = [...root.querySelectorAll("[data-archive-count]")];
    const empty = document.querySelector("[data-archive-empty]");
    const clearButton = root.querySelector("[data-archive-clear]");
    const chips = root.querySelector("[data-filter-chips]");
    const filters = root.querySelector("[data-archive-filters]");
    const filterToggle = root.querySelector("[data-filter-toggle]");
    const filterBackdrop = root.querySelector("[data-filter-backdrop]");
    const lockedCategory = root.getAttribute("data-locked-category") || "";
    const rows = [...grid.querySelectorAll("article")];
    const params = new URLSearchParams(window.location.search);
    const facetKeys = ["brand", "abv", "category", "production", "method"];
    const facetLabels = {
      brand: "Brand",
      abv: "ABV",
      category: "Category",
      production: "Production type",
      method: "Method",
    };
    const legacyProduction = {
      yes: "dealcoholized",
      no: "alternative",
      "not-verified": "not-verified",
    };
    const legacyMethod = {
      unpublished: "unknown",
      "reverse-distillation": "other",
    };

    const selectedValues = (name) =>
      [...root.querySelectorAll(`[data-archive-${name}]:checked`)].map((input) => input.value);

    const setChecked = (name, values) => {
      root.querySelectorAll(`[data-archive-${name}]`).forEach((input) => {
        input.checked = values.includes(input.value);
      });
    };

    const readList = (key) =>
      (params.get(key) || "")
        .split(",")
        .map((value) => value.trim())
        .filter(Boolean);

    if (queryInput) {
      queryInput.value = params.get("q") || "";
    }

    if (!lockedCategory) {
      setChecked("category", readList("category"));
    }

    setChecked("brand", readList("brand"));
    setChecked("abv", readList("abv").map((value) => (value === "trace" ? "half" : value)));
    setChecked("production", readList("production").map((value) => legacyProduction[value] || value));
    if (!selectedValues("production").length) {
      setChecked("production", readList("dealcoholized").map((value) => legacyProduction[value] || value));
    }
    setChecked("method", readList("method").map((value) => legacyMethod[value] || value));

    if (sortSelect) {
      const options = [...sortSelect.options].map((option) => option.value);
      const sort = params.get("sort") || "newest";
      sortSelect.value = options.includes(sort) ? sort : "newest";
    }

    const writeUrl = () => {
      const next = new URLSearchParams();
      const query = queryInput?.value.trim() || "";

      if (query) {
        next.set("q", query);
      }

      facetKeys.forEach((key) => {
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
      const url = qs ? `${window.location.pathname}?${qs}` : window.location.pathname;
      window.history.replaceState({}, "", url);
    };

    const matchesFacets = (row, skip = "") => {
      const query = (queryInput?.value || "").trim().toLowerCase();
      const terms = query ? query.split(/\s+/) : [];
      const search = row.getAttribute("data-search") || "";
      const matchQuery = terms.every((term) => search.includes(term));
      const category = lockedCategory || (skip === "category" ? "" : selectedValues("category"));
      const matchCategory = Array.isArray(category)
        ? !category.length || category.includes(row.getAttribute("data-category") || "")
        : !category || row.getAttribute("data-category") === category;

      const matchFacet = (name, attr) => {
        if (skip === name) {
          return true;
        }

        const values = selectedValues(name);

        return !values.length || values.includes(row.getAttribute(attr) || "");
      };

      return matchQuery
        && matchCategory
        && matchFacet("brand", "data-brand")
        && matchFacet("abv", "data-abv")
        && matchFacet("production", "data-production")
        && matchFacet("method", "data-method");
    };

    const updateCounts = () => {
      root.querySelectorAll("[data-facet]").forEach((group) => {
        const name = group.getAttribute("data-facet");
        const attr = `data-${name}`;

        group.querySelectorAll(".facet-option").forEach((option) => {
          const input = option.querySelector("input");
          const total = rows.filter((row) => {
            if (!matchesFacets(row, name)) {
              return false;
            }

            return (row.getAttribute(attr) || "") === input.value;
          }).length;
          const countNode = option.querySelector("[data-facet-count]");

          if (countNode) {
            countNode.textContent = String(total);
          }

          option.hidden = total === 0 && !input.checked;
        });
      });
    };

    const renderChips = () => {
      if (!chips) {
        return;
      }

      const items = [];
      const query = queryInput?.value.trim() || "";

      if (query) {
        items.push({ key: "q", value: query, label: `Search: ${query}` });
      }

      facetKeys.forEach((key) => {
        if (key === "category" && lockedCategory) {
          return;
        }

        selectedValues(key).forEach((value) => {
          const input = root.querySelector(`[data-archive-${key}][value="${CSS.escape(value)}"]`);
          const label = input?.closest("label")?.querySelector("span")?.textContent || value;
          items.push({ key, value, label: `${facetLabels[key]}: ${label}` });
        });
      });

      chips.hidden = items.length === 0;
      chips.innerHTML = items.map((item) => (
        `<button type="button" class="filter-chip" data-chip-key="${item.key}" data-chip-value="${item.value}">${item.label}<span aria-hidden="true">×</span></button>`
      )).join("");
    };

    const apply = (updateHistory = true) => {
      const sortBy = sortSelect?.value || "newest";
      const visible = rows.filter((row) => {
        const show = matchesFacets(row);
        row.hidden = !show;

        return show;
      });

      visible
        .sort((a, b) => {
          if (sortBy === "rating") {
            return Number(b.getAttribute("data-rating") || 0) - Number(a.getAttribute("data-rating") || 0);
          }

          if (sortBy === "title") {
            return (a.querySelector("h3")?.textContent || "").localeCompare(b.querySelector("h3")?.textContent || "");
          }

          return (b.getAttribute("data-date") || "").localeCompare(a.getAttribute("data-date") || "");
        })
        .forEach((row) => grid.appendChild(row));

      const noun = rows.length === 1 ? "review" : "reviews";
      const label = visible.length === rows.length
        ? `${rows.length} ${noun}`
        : `${visible.length} of ${rows.length} ${noun}`;

      countNodes.forEach((node) => {
        node.textContent = label;
      });

      if (empty && rows.length) {
        empty.hidden = visible.length > 0;
      }

      updateCounts();
      renderChips();

      if (updateHistory) {
        writeUrl();
      }
    };

    const setFiltersOpen = (open) => {
      filters?.classList.toggle("is-open", open);
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
          const matchesFind = !needle || label.includes(needle);
          option.classList.toggle("is-filtered", !matchesFind);
        });
      });
    });

    root.addEventListener("input", (event) => {
      if (event.target.matches("[data-facet-find]")) {
        return;
      }

      apply();
    });
    root.addEventListener("change", () => apply());
    root.addEventListener("submit", (event) => {
      event.preventDefault();
      apply();
      setFiltersOpen(false);
    });

    chips?.addEventListener("click", (event) => {
      const chip = event.target.closest("[data-chip-key]");

      if (!chip) {
        return;
      }

      const key = chip.getAttribute("data-chip-key");
      const value = chip.getAttribute("data-chip-value") || "";

      if (key === "q" && queryInput) {
        queryInput.value = "";
      } else if (key) {
        root.querySelector(`[data-archive-${key}][value="${CSS.escape(value)}"]`)?.click();
        return;
      }

      apply();
    });

    clearButton?.addEventListener("click", () => {
      if (queryInput) {
        queryInput.value = "";
      }

      root.querySelectorAll('input[type="checkbox"]').forEach((input) => {
        input.checked = false;
      });

      root.querySelectorAll("[data-facet-find]").forEach((input) => {
        input.value = "";
        input.closest("[data-facet]")?.querySelectorAll(".facet-option").forEach((option) => {
          option.classList.remove("is-filtered");
        });
      });

      if (sortSelect) {
        sortSelect.value = "newest";
      }

      apply();
    });

    filterToggle?.addEventListener("click", () => {
      setFiltersOpen(!filters?.classList.contains("is-open"));
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

    apply(false);
  };

  chrome();
  directory();
  archive();
})();
