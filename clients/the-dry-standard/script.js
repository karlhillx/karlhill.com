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

  const filter = document.querySelector("[data-filter-dealcoholized]");
  const cards = document.querySelectorAll("[data-review-grid] [data-dealcoholized]");

  if (filter && cards.length) {
    filter.addEventListener("change", () => {
      const value = filter.value;
      cards.forEach((card) => {
        const match = value === "all" || card.getAttribute("data-dealcoholized") === value;
        card.hidden = !match;
      });
    });
  }
})();
