import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

export function cardRails() {
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
  }
