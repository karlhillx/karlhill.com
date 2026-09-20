(() => {
  const body = document.body;
  const focusableSelector = 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])';

  const setLocked = (locked) => {
    body.classList.toggle("is-locked", locked);
  };

  const analyticsEnabled = () => body.getAttribute("data-analytics") === "1";

  const track = (name, detail = {}) => {
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

  const trapFocus = (root, event) => {
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

  const chrome = () => {
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
  };

  const archive = () => {
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
  };

  const analytics = () => {
    document.addEventListener("click", (event) => {
      const target = event.target.closest("[data-analytics-event]");
      if (!target) {
        return;
      }

      track(target.getAttribute("data-analytics-event"), {
        href: target.getAttribute("href") || "",
      });
    });

    const review = document.querySelector("[data-analytics-view]");
    if (review) {
      track(review.getAttribute("data-analytics-view") || "review_view", {
        href: review.getAttribute("data-slug") || window.location.pathname,
      });
    }

    document.querySelectorAll("[data-analytics-search]").forEach((input) => {
      const form = input.closest("form");
      if (!form) {
        return;
      }
      form.addEventListener("submit", () => {
        track("search_submit", { q: input.value || "" });
      });
    });

    document.querySelectorAll("[data-analytics-filters]").forEach((form) => {
      form.addEventListener("submit", () => {
        const params = new URLSearchParams(new FormData(form));
        track("filter_use", { query: params.toString() });
      });
    });

    document.querySelectorAll('a[href*="/methods/"]').forEach((link) => {
      link.setAttribute("data-analytics-event", link.getAttribute("data-analytics-event") || "method_page");
    });

    document.querySelectorAll('a[href*="/industry/submit"]').forEach((link) => {
      link.setAttribute("data-analytics-event", link.getAttribute("data-analytics-event") || "product_submit_click");
    });
  };

  const compareTray = () => {
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
      tray.setAttribute("data-compare-tray");
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
  };

  const saveTray = () => {
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
  };

  const reviewSticky = () => {
    const bar = document.querySelector("[data-review-sticky]");
    const score = document.querySelector("[data-review-score]");
    if (!bar || !score || window.matchMedia("(min-width: 721px)").matches) {
      return;
    }

    const update = () => {
      const rect = score.getBoundingClientRect();
      bar.hidden = rect.bottom >= 0;
    };

    window.addEventListener("scroll", update, { passive: true });
    window.addEventListener("resize", update);
    update();
  };

  const reviewSubnav = () => {
    const subnav = document.querySelector("[data-review-subnav]");
    const hero = document.querySelector(".review-hero");
    if (!subnav || !hero || window.matchMedia("(max-width: 720px)").matches) {
      return;
    }

    const navLinks = [...document.querySelectorAll(".review-section-nav a[data-section-target]")];
    const targets = navLinks
      .map((link) => document.getElementById(link.getAttribute("data-section-target") || ""))
      .filter(Boolean);

    const setActive = (id) => {
      document.querySelectorAll(".review-section-nav a[data-section-target]").forEach((link) => {
        link.classList.toggle("is-active", link.getAttribute("data-section-target") === id);
      });
    };

    const updateVisibility = () => {
      subnav.hidden = hero.getBoundingClientRect().bottom > 12;
    };

    window.addEventListener("scroll", updateVisibility, { passive: true });
    window.addEventListener("resize", updateVisibility);
    updateVisibility();

    if (targets.length === 0 || !("IntersectionObserver" in window)) {
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        const visible = entries
          .filter((entry) => entry.isIntersecting)
          .sort((a, b) => b.intersectionRatio - a.intersectionRatio);
        if (visible[0]) {
          setActive(visible[0].target.id);
        }
      },
      { rootMargin: "-20% 0px -55% 0px", threshold: [0.1, 0.35, 0.6] },
    );

    targets.forEach((target) => observer.observe(target));
  };

  const scrollToId = (id) => {
    const target = id ? document.getElementById(id) : null;
    if (!target) {
      return false;
    }

    target.scrollIntoView();
    return true;
  };

  const inPageJump = () => {
    document.addEventListener("click", (event) => {
      const link = event.target.closest("a[href]");
      if (!link || event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
      }

      let next;
      try {
        next = new URL(link.href, window.location.href);
      } catch {
        return;
      }

      if (next.origin !== window.location.origin || next.pathname !== window.location.pathname || next.search !== window.location.search) {
        return;
      }

      const id = decodeURIComponent(next.hash.replace(/^#/, ""));
      if (!id || !scrollToId(id)) {
        return;
      }

      event.preventDefault();
      if (window.location.hash !== `#${id}`) {
        history.replaceState(null, "", `#${id}`);
      }
    });

    if (location.hash.length > 1) {
      scrollToId(decodeURIComponent(location.hash.slice(1)));
    }
  };

  const reveal = () => {
    const nodes = [...document.querySelectorAll("[data-reveal]")];
    const mark = (node) => node.classList.add("is-inview");
    const nearViewport = (node) => {
      const rect = node.getBoundingClientRect();
      return rect.top < window.innerHeight * 0.94 && rect.bottom > 0;
    };

    if (!nodes.length) {
      document.documentElement.classList.add("js");
      return;
    }

    // Mark what is already on screen before enabling hide-until-reveal CSS.
    nodes.filter(nearViewport).forEach(mark);
    document.documentElement.classList.add("js");

    const pending = nodes.filter((node) => !node.classList.contains("is-inview"));
    if (!pending.length) {
      return;
    }

    if (!("IntersectionObserver" in window)) {
      pending.forEach(mark);
      return;
    }

    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) {
            return;
          }
          mark(entry.target);
          observer.unobserve(entry.target);
        });
      },
      { rootMargin: "0px 0px -6% 0px", threshold: 0.08 },
    );

    pending.forEach((node) => observer.observe(node));
  };

  const cardRails = () => {
    const rails = [...document.querySelectorAll("[data-card-rail]")];
    if (!rails.length) {
      return;
    }

    const sync = (rail) => {
      const track = rail.querySelector(".card-grid--rail");
      if (!track) {
        return;
      }

      const max = track.scrollWidth - track.clientWidth;
      const scrollable = max > 8;
      rail.classList.toggle("is-scrollable", scrollable);
      if (!scrollable) {
        rail.classList.remove("show-start", "show-end");
        return;
      }

      rail.classList.toggle("show-start", track.scrollLeft > 8);
      rail.classList.toggle("show-end", track.scrollLeft < max - 8);
    };

    rails.forEach((rail) => {
      const track = rail.querySelector(".card-grid--rail");
      if (!track) {
        return;
      }

      sync(rail);
      track.addEventListener("scroll", () => sync(rail), { passive: true });
      window.addEventListener("resize", () => sync(rail), { passive: true });
    });
  };

  chrome();
  directory();
  archive();
  compareTray();
  saveTray();
  reviewSticky();
  reviewSubnav();
  analytics();
  inPageJump();
  reveal();
  cardRails();
  window.addEventListener("popstate", () => {
    const root = document.querySelector("[data-archive]");
    if (root && typeof root._swapFromLocation === "function") {
      root._swapFromLocation();
    }
  });
})();
