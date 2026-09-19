(() => {
  const nav = document.querySelector("[data-nav]");
  const toggle = document.querySelector("[data-nav-toggle]");
  const year = document.querySelector("[data-year]");

  if (year) {
    year.textContent = String(new Date().getFullYear());
  }

  if (toggle && nav) {
    toggle.addEventListener("click", () => {
      const open = toggle.getAttribute("aria-expanded") === "true";
      toggle.setAttribute("aria-expanded", String(!open));
      nav.classList.toggle("is-open", !open);
    });

    nav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        toggle.setAttribute("aria-expanded", "false");
        nav.classList.remove("is-open");
      });
    });
  }

  const archive = document.querySelector("[data-archive]");
  const grid = document.querySelector("[data-review-grid]");

  if (!archive || !grid) {
    return;
  }

  const queryInput = archive.querySelector("[data-archive-q]");
  const sortSelect = archive.querySelector("[data-archive-sort]");
  const count = archive.querySelector("[data-archive-count]");
  const empty = document.querySelector("[data-archive-empty]");
  const clearButton = archive.querySelector("[data-archive-clear]");
  const lockedCategory = archive.getAttribute("data-locked-category") || "";
  const rows = [...grid.querySelectorAll("article")];
  const params = new URLSearchParams(window.location.search);
  const facetKeys = ["brand", "abv", "category", "dealcoholized", "method"];

  const selectedValues = (name) =>
    [...archive.querySelectorAll(`[data-archive-${name}]:checked`)].map((input) => input.value);

  const setChecked = (name, values) => {
    archive.querySelectorAll(`[data-archive-${name}]`).forEach((input) => {
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
  setChecked("abv", readList("abv"));
  setChecked("dealcoholized", readList("dealcoholized"));
  setChecked("method", readList("method"));

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
      && matchFacet("dealcoholized", "data-dealcoholized")
      && matchFacet("method", "data-method");
  };

  const updateCounts = () => {
    archive.querySelectorAll("[data-facet]").forEach((group) => {
      const name = group.getAttribute("data-facet");
      const attr = name === "dealcoholized" ? "data-dealcoholized" : `data-${name}`;

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

    if (count) {
      const noun = rows.length === 1 ? "review" : "reviews";
      count.textContent = visible.length === rows.length
        ? `${rows.length} ${noun}`
        : `${visible.length} of ${rows.length} ${noun}`;
    }

    if (empty && rows.length) {
      empty.hidden = visible.length > 0;
    }

    updateCounts();

    if (updateHistory) {
      writeUrl();
    }
  };

  archive.querySelectorAll("[data-facet-find]").forEach((input) => {
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

  archive.addEventListener("input", (event) => {
    if (event.target.matches("[data-facet-find]")) {
      return;
    }

    apply();
  });
  archive.addEventListener("change", () => apply());
  archive.addEventListener("submit", (event) => {
    event.preventDefault();
    apply();
  });

  clearButton?.addEventListener("click", () => {
    if (queryInput) {
      queryInput.value = "";
    }

    archive.querySelectorAll('input[type="checkbox"]').forEach((input) => {
      input.checked = false;
    });

    archive.querySelectorAll("[data-facet-find]").forEach((input) => {
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

  apply(false);
})();
