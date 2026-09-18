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
  const categorySelect = archive.querySelector("[data-archive-category]");
  const processSelect = archive.querySelector("[data-archive-dealcoholized]");
  const methodSelect = archive.querySelector("[data-archive-method]");
  const sortSelect = archive.querySelector("[data-archive-sort]");
  const count = archive.querySelector("[data-archive-count]");
  const empty = document.querySelector("[data-archive-empty]");
  const lockedCategory = archive.getAttribute("data-locked-category") || "";
  const rows = [...grid.querySelectorAll("article")];
  const params = new URLSearchParams(window.location.search);

  const setValue = (field, value) => {
    if (!field || field.disabled) {
      return;
    }

    const options = [...field.options].map((option) => option.value);
    field.value = options.includes(value) ? value : "";
  };

  if (queryInput) {
    queryInput.value = params.get("q") || "";
  }

  if (!lockedCategory) {
    setValue(categorySelect, params.get("category") || "");
  }

  setValue(processSelect, params.get("dealcoholized") || "");
  setValue(methodSelect, params.get("method") || "");
  setValue(sortSelect, params.get("sort") || "newest");

  if (sortSelect && !sortSelect.value) {
    sortSelect.value = "newest";
  }

  const writeUrl = () => {
    const next = new URLSearchParams();
    const query = queryInput?.value.trim() || "";

    if (query) {
      next.set("q", query);
    }

    if (!lockedCategory && categorySelect?.value) {
      next.set("category", categorySelect.value);
    }

    if (processSelect?.value) {
      next.set("dealcoholized", processSelect.value);
    }

    if (methodSelect?.value) {
      next.set("method", methodSelect.value);
    }

    if (sortSelect?.value && sortSelect.value !== "newest") {
      next.set("sort", sortSelect.value);
    }

    const qs = next.toString();
    const url = qs ? `${window.location.pathname}?${qs}` : window.location.pathname;
    window.history.replaceState({}, "", url);
  };

  const apply = (updateHistory = true) => {
    const query = (queryInput?.value || "").trim().toLowerCase();
    const terms = query ? query.split(/\s+/) : [];
    const category = lockedCategory || categorySelect?.value || "";
    const process = processSelect?.value || "";
    const method = methodSelect?.value || "";
    const sortBy = sortSelect?.value || "newest";

    const visible = rows.filter((row) => {
      const search = row.getAttribute("data-search") || "";
      const matchQuery = terms.every((term) => search.includes(term));
      const matchCategory = !category || row.getAttribute("data-category") === category;
      const matchProcess = !process || row.getAttribute("data-dealcoholized") === process;
      const matchMethod = !method || row.getAttribute("data-method") === method;
      const show = matchQuery && matchCategory && matchProcess && matchMethod;
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

    if (updateHistory) {
      writeUrl();
    }
  };

  apply(false);

  archive.addEventListener("input", () => apply());
  archive.addEventListener("change", () => apply());
  archive.addEventListener("submit", (event) => {
    event.preventDefault();
    apply();
  });
})();
