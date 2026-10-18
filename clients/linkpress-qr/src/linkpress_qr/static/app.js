/* Linkpress QR — interface logic (no dependencies, no build step). */
(() => {
  "use strict";

  const $ = (id) => document.getElementById(id);
  const form = $("qr-form");
  const urlInput = $("url");
  const urlError = $("url-error");
  const generateBtn = $("generate-btn");
  const resetBtn = $("reset-btn");
  const outerField = $("outer-field");
  const borderField = $("border-field");
  const resultPanel = $("result-panel");
  const stage = $("stage");
  const stageEmpty = $("stage-empty");
  const previewImg = $("preview-img");
  const staleBadge = $("stale-badge");
  const details = $("result-details");
  const destinationEl = $("destination");
  const metaEl = $("meta");
  const pngBtn = $("download-png");
  const svgBtn = $("download-svg");
  const downloadHint = $("download-hint");
  const ppiSelect = $("ppi");
  const printSizeEl = $("print-size");
  const statusEl = $("status");

  const DEFAULT_SIZE = "1024";

  /** The last successful generation. Preview, destination and downloads all derive from it. */
  let result = null;
  let previewUrl = null;
  let requestSeq = 0;

  const radioValue = (name) => form.querySelector(`input[name="${name}"]:checked`)?.value
    ?? document.querySelector(`input[name="${name}"]:checked`)?.value;

  function currentSettings() {
    return {
      url: urlInput.value.trim(),
      preset: radioValue("preset"),
      outer_background: radioValue("outer_background"),
      card_border: radioValue("card_border"),
    };
  }

  function sameDesign(a, b) {
    if (a.url !== b.url || a.preset !== b.preset) return false;
    // The outer canvas option only applies to the rounded card.
    return a.preset === "square" || (
      a.outer_background === b.outer_background && a.card_border === b.card_border
    );
  }

  function isStale() {
    return result !== null && !sameDesign(currentSettings(), result.settings);
  }

  function setStatus(message, isError = false) {
    statusEl.textContent = message;
    statusEl.classList.toggle("is-error", isError);
  }

  function showFieldError(message) {
    urlError.textContent = message;
    urlError.hidden = false;
    urlInput.setAttribute("aria-invalid", "true");
  }

  function clearFieldError() {
    urlError.hidden = true;
    urlError.textContent = "";
    urlInput.removeAttribute("aria-invalid");
  }

  function syncOuterField() {
    const square = radioValue("preset") === "square";
    outerField.hidden = square;
    outerField.disabled = square;
    borderField.hidden = square;
    borderField.disabled = square;
  }

  function syncDownloads() {
    const stale = isStale();
    const ready = result !== null && !stale;
    pngBtn.disabled = !ready;
    svgBtn.disabled = !ready;
    stage.classList.toggle("is-stale", stale);
    staleBadge.hidden = !stale;
    if (!result) {
      downloadHint.textContent = "Generate a QR code to enable downloads.";
    } else if (stale) {
      downloadHint.textContent = "The link or style changed. Generate again to download the updated QR code.";
    } else {
      downloadHint.textContent = "Downloads match the preview above.";
    }
  }

  function formatInches(px, ppi) {
    const inches = px / ppi;
    const mm = inches * 25.4;
    return `${inches.toFixed(2)} in × ${inches.toFixed(2)} in (${mm.toFixed(1)} mm)`;
  }

  function updatePrintSize() {
    const size = Number(radioValue("png_size") || DEFAULT_SIZE);
    const ppi = Number(ppiSelect.value);
    if (ppi) {
      printSizeEl.textContent = `${size} × ${size} px at ${ppi} PPI prints at ${formatInches(size, ppi)}.`;
    } else {
      printSizeEl.textContent =
        `No PPI metadata. For reference, ${size} px is ${formatInches(size, 300)} at 300 PPI.`;
    }
  }

  function setLoading(loading) {
    generateBtn.setAttribute("aria-busy", String(loading));
    generateBtn.querySelector(".btn-label").textContent = loading ? "Generating…" : "Generate QR code";
    resultPanel.setAttribute("aria-busy", String(loading));
    stage.classList.toggle("is-loading", loading);
  }

  function showResult(settings, data) {
    if (previewUrl) URL.revokeObjectURL(previewUrl);
    previewUrl = URL.createObjectURL(new Blob([data.svg], { type: "image/svg+xml" }));
    result = { settings, data };

    previewImg.src = previewUrl;
    previewImg.alt = `QR code that opens ${data.destination}`;
    previewImg.hidden = false;
    stageEmpty.hidden = true;

    destinationEl.textContent = data.destination;
    const style = data.preset === "square"
      ? "Classic square"
      : `Rounded card, ${data.outer_background} outside`;
    metaEl.textContent =
      `${style}, ${data.card_border === "black" ? "black border" : "no border"} · ` +
      `QR version ${data.version} (${data.modules} × ${data.modules} modules) · ` +
      `error correction ${data.error_correction}`;
    details.hidden = false;
    syncDownloads();
  }

  function clearResult() {
    result = null;
    if (previewUrl) URL.revokeObjectURL(previewUrl);
    previewUrl = null;
    previewImg.removeAttribute("src");
    previewImg.alt = "";
    previewImg.hidden = true;
    stageEmpty.hidden = false;
    details.hidden = true;
    destinationEl.textContent = "";
    metaEl.textContent = "";
    syncDownloads();
  }

  async function readError(response) {
    try {
      const body = await response.json();
      if (body && body.error) return body.error;
    } catch (_) { /* fall through */ }
    return { message: `Something went wrong (HTTP ${response.status}).`, field: null };
  }

  async function generate(event) {
    event.preventDefault();
    clearFieldError();
    const settings = currentSettings();
    if (!settings.url) {
      showFieldError("Paste a link to generate a QR code.");
      urlInput.focus();
      return;
    }

    const seq = ++requestSeq;
    setLoading(true);
    setStatus("Generating QR code…");
    try {
      const response = await fetch("api/generate", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(settings),
      });
      if (seq !== requestSeq) return; // a newer request superseded this one
      if (!response.ok) {
        const error = await readError(response);
        if (seq !== requestSeq) return;
        if (error.field === "url") {
          showFieldError(error.message);
          urlInput.focus();
          setStatus("");
        } else {
          setStatus(error.message, true);
        }
        syncDownloads();
        return;
      }
      const data = await response.json();
      if (seq !== requestSeq) return;
      showResult(settings, data);
      setStatus("QR code ready. Check the destination, then download.");
    } catch (_) {
      if (seq !== requestSeq) return;
      setStatus("Couldn't reach Linkpress QR. Is the app still running?", true);
    } finally {
      if (seq === requestSeq) setLoading(false);
    }
  }

  function filenameFrom(response, fallback) {
    const header = response.headers.get("Content-Disposition") || "";
    const match = /filename="([^"]+)"/.exec(header);
    return match ? match[1] : fallback;
  }

  async function download(kind, button) {
    if (!result || isStale()) return;
    const { settings } = result;
    const payload = {
      url: settings.url,
      preset: settings.preset,
      outer_background: settings.outer_background,
      card_border: settings.card_border,
    };
    if (kind === "png") {
      payload.size = Number(radioValue("png_size") || DEFAULT_SIZE);
      payload.ppi = ppiSelect.value ? Number(ppiSelect.value) : null;
    }
    button.setAttribute("aria-busy", "true");
    button.disabled = true;
    setStatus(`Preparing ${kind.toUpperCase()}…`);
    try {
      const response = await fetch(`api/export/${kind}`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload),
      });
      if (!response.ok) {
        const error = await readError(response);
        setStatus(error.message, true);
        return;
      }
      const blob = await response.blob();
      const name = filenameFrom(response, `linkpress-qr.${kind}`);
      const href = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = href;
      a.download = name;
      document.body.appendChild(a);
      a.click();
      a.remove();
      setTimeout(() => URL.revokeObjectURL(href), 10000);
      setStatus(`Downloaded ${name}`);
    } catch (_) {
      setStatus("Couldn't reach Linkpress QR. Is the app still running?", true);
    } finally {
      button.removeAttribute("aria-busy");
      syncDownloads();
    }
  }

  function reset() {
    requestSeq++;
    setLoading(false);
    form.reset();
    document.getElementById(`size-${DEFAULT_SIZE}`).checked = true;
    ppiSelect.value = "";
    clearFieldError();
    clearResult();
    syncOuterField();
    updatePrintSize();
    setStatus("");
    urlInput.focus();
  }

  form.addEventListener("submit", generate);
  urlInput.addEventListener("input", () => {
    if (!urlError.hidden) clearFieldError();
    syncDownloads();
  });
  form.addEventListener("change", () => {
    syncOuterField();
    syncDownloads();
  });
  document.querySelectorAll('input[name="png_size"]').forEach((el) =>
    el.addEventListener("change", updatePrintSize));
  ppiSelect.addEventListener("change", updatePrintSize);
  pngBtn.addEventListener("click", () => download("png", pngBtn));
  svgBtn.addEventListener("click", () => download("svg", svgBtn));
  resetBtn.addEventListener("click", reset);

  syncOuterField();
  syncDownloads();
  updatePrintSize();
})();
