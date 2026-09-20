import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function reviewSticky() {
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
  }

export function reviewSubnav() {
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
  }
