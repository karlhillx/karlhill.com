import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function analytics() {
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
  }
