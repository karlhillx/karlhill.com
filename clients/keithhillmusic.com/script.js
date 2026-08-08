(() => {
  const header = document.querySelector("[data-header]");
  const nav = document.querySelector("[data-nav]");
  const navToggle = document.querySelector("[data-nav-toggle]");
  const year = document.querySelector("[data-year]");

  if (year) {
    year.textContent = String(new Date().getFullYear());
  }

  const onScroll = () => {
    if (!header) return;
    header.classList.toggle("is-scrolled", window.scrollY > 24);
  };

  onScroll();
  window.addEventListener("scroll", onScroll, { passive: true });

  if (navToggle && nav) {
    navToggle.addEventListener("click", () => {
      const open = navToggle.getAttribute("aria-expanded") === "true";
      navToggle.setAttribute("aria-expanded", String(!open));
      nav.classList.toggle("is-open", !open);
    });

    nav.querySelectorAll("a").forEach((link) => {
      link.addEventListener("click", () => {
        navToggle.setAttribute("aria-expanded", "false");
        nav.classList.remove("is-open");
      });
    });
  }

  // Scroll reveal
  const reveals = document.querySelectorAll(".reveal");
  if ("IntersectionObserver" in window && reveals.length) {
    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-in");
            io.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.12, rootMargin: "0px 0px -8% 0px" }
    );
    reveals.forEach((el) => io.observe(el));
  } else {
    reveals.forEach((el) => el.classList.add("is-in"));
  }

  // Music player tabs
  const tabs = document.querySelectorAll("[data-player-tab]");
  const panels = document.querySelectorAll("[data-player-panel]");

  tabs.forEach((tab) => {
    tab.addEventListener("click", () => {
      const id = tab.getAttribute("data-player-tab");
      tabs.forEach((t) => {
        const active = t === tab;
        t.classList.toggle("is-active", active);
        t.setAttribute("aria-selected", String(active));
      });
      panels.forEach((panel) => {
        const match = panel.getAttribute("data-player-panel") === id;
        panel.classList.toggle("is-active", match);
        panel.hidden = !match;
      });
    });
  });

  // Gallery lightbox
  const lightbox = document.querySelector("[data-lightbox]");
  const lightboxStage = document.querySelector("[data-lightbox-stage]");
  const lightboxClose = document.querySelector("[data-lightbox-close]");
  const galleryItems = document.querySelectorAll("[data-gallery-item]");

  const closeLightbox = () => {
    if (!lightbox || !lightboxStage) return;
    lightbox.hidden = true;
    lightboxStage.innerHTML = "";
    document.body.style.overflow = "";
  };

  const openLightbox = (item) => {
    if (!lightbox || !lightboxStage) return;
    const type = item.getAttribute("data-type");
    const href = item.getAttribute("href");
    lightboxStage.innerHTML = "";

    if (type === "video") {
      const isFile = /\.(mp4|webm|ogg)(\?|$)/i.test(href || "");
      if (isFile) {
        const video = document.createElement("video");
        video.src = href;
        video.controls = true;
        video.autoplay = true;
        video.playsInline = true;
        video.setAttribute("playsinline", "");
        lightboxStage.appendChild(video);
      } else {
        const iframe = document.createElement("iframe");
        iframe.src = `${href}?autoplay=1`;
        iframe.title = "Video";
        iframe.allow =
          "accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture";
        iframe.allowFullscreen = true;
        lightboxStage.appendChild(iframe);
      }
    } else {
      const img = document.createElement("img");
      img.src = href;
      img.alt = item.querySelector("img")?.alt || "";
      lightboxStage.appendChild(img);
    }

    lightbox.hidden = false;
    document.body.style.overflow = "hidden";
    lightboxClose?.focus();
  };

  galleryItems.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      openLightbox(item);
    });
  });

  lightboxClose?.addEventListener("click", closeLightbox);
  lightbox?.addEventListener("click", (event) => {
    if (event.target === lightbox) closeLightbox();
  });

  document.addEventListener("keydown", (event) => {
    if (event.key === "Escape" && lightbox && !lightbox.hidden) {
      closeLightbox();
    }
  });

  // Forms — mailto fallback (wire to Formspree / Mailchimp later)
  const mailingForm = document.querySelector("[data-mailing-form]");
  const mailingStatus = document.querySelector("[data-mailing-status]");
  const contactForm = document.querySelector("[data-contact-form]");
  const contactStatus = document.querySelector("[data-contact-status]");

  const setStatus = (el, message, type) => {
    if (!el) return;
    el.textContent = message;
    el.classList.remove("is-error", "is-success");
    if (type) el.classList.add(type);
  };

  mailingForm?.addEventListener("submit", (event) => {
    event.preventDefault();
    const email = new FormData(mailingForm).get("email");
    if (!email || !String(email).includes("@")) {
      setStatus(mailingStatus, "Please enter a valid email.", "is-error");
      return;
    }
    setStatus(
      mailingStatus,
      "You’re on the list. Watch your inbox for show news.",
      "is-success"
    );
    mailingForm.reset();
  });

  contactForm?.addEventListener("submit", (event) => {
    event.preventDefault();
    const data = new FormData(contactForm);
    const name = String(data.get("name") || "").trim();
    const email = String(data.get("email") || "").trim();
    const subject = String(data.get("subject") || "").trim();
    const message = String(data.get("message") || "").trim();

    if (!name || !email || !subject || !message) {
      setStatus(contactStatus, "Please fill out every field.", "is-error");
      return;
    }

    const body = [
      `Name: ${name}`,
      `Email: ${email}`,
      "",
      message,
    ].join("\n");

    const mailto = `mailto:Contact@keithhillmusic.com?subject=${encodeURIComponent(
      subject
    )}&body=${encodeURIComponent(body)}`;

    setStatus(
      contactStatus,
      "Opening your email app to send the message…",
      "is-success"
    );
    window.location.href = mailto;
  });
})();
