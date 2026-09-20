import { body, focusableSelector, setLocked, trapFocus, track, analyticsEnabled } from "./shared.js";

function scrollToId(id) {
  const target = id ? document.getElementById(id) : null;
  if (!target) {
    return false;
  }

  if (id === "provenance") {
    const panel = target.querySelector("details[data-provenance-panel]") || target.closest("details[data-provenance-panel]");
    if (panel && !panel.open) {
      panel.open = true;
    }
  }

  target.scrollIntoView();
  requestAnimationFrame(() => target.scrollIntoView());
  return true;
}

export function inPageJump() {
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
  }
