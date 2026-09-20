import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function reveal() {
    const nodes = [...document.querySelectorAll("[data-reveal]")];
    const mark = (node) => node.classList.add("is-inview");
    // Include just-below-fold sections so a tall hero does not leave the next block blank.
    const nearViewport = (node) => {
      const rect = node.getBoundingClientRect();
      return rect.top < window.innerHeight * 1.35 && rect.bottom > -80;
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
      { rootMargin: "20% 0px", threshold: 0.01 },
    );

    pending.forEach((node) => observer.observe(node));
  }
