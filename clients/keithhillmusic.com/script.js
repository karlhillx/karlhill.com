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
      const video = document.createElement("video");
      video.src = href;
      video.controls = true;
      video.autoplay = true;
      video.playsInline = true;
      video.setAttribute("playsinline", "");
      lightboxStage.appendChild(video);
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
})();
