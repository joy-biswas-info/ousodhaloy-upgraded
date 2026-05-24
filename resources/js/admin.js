// Admin JS — loaded on all admin pages

// Confirm dangerous actions
document.querySelectorAll("[data-confirm]").forEach((el) => {
  el.addEventListener("click", (e) => {
    if (!confirm(el.dataset.confirm)) e.preventDefault();
  });
});

// Auto-dismiss flash messages after 4s
document.querySelectorAll(".flash-msg").forEach((el) => {
  setTimeout(() => el.remove(), 4000);
});

// Select all checkboxes
const selectAll = document.getElementById("select-all");
if (selectAll) {
  selectAll.addEventListener("change", () => {
    document.querySelectorAll(".order-cb, .product-cb").forEach((cb) => {
      cb.checked = selectAll.checked;
    });
  });
}

// Image preview on file input
document.querySelectorAll("input[type=file][data-preview]").forEach((input) => {
  input.addEventListener("change", () => {
    const previewId = input.dataset.preview;
    const preview = document.getElementById(previewId);
    if (preview && input.files[0]) {
      const reader = new FileReader();
      reader.onload = (e) => {
        preview.src = e.target.result;
        preview.style.display = "block";
      };
      reader.readAsDataURL(input.files[0]);
    }
  });
});

// Slug auto-gen from title
const nameInput = document.querySelector("[data-slug-source]");
const slugInput = document.querySelector("[data-slug-target]");
if (nameInput && slugInput) {
  let slugEdited = slugInput.value !== "";
  slugInput.addEventListener("input", () => {
    slugEdited = true;
  });
  nameInput.addEventListener("input", () => {
    if (!slugEdited) {
      slugInput.value = nameInput.value
        .toLowerCase()
        .replace(/[^\w\s-]/g, "")
        .replace(/[\s_]+/g, "-")
        .replace(/^-+|-+$/g, "");
    }
  });
}

// ── Universal Media Picker ─────────────────────────────────────────────────
// openMediaPicker(id, callback)  — opens the picker
// callback(path, url, filename)  — fired when user confirms selection

let _mpCallback = null;
let _mpSelected = null; // { path, url, filename }
let _mpDebounce = null;
let _mpQueue = []; // files staged for upload

window.openMediaPicker = function (id, callback) {
  _mpCallback = callback;
  _mpSelected = null;
  _mpQueue = [];
  document.getElementById("media-picker-modal").classList.add("open");
  document.body.style.overflow = "hidden";
  mpSwitchTab("browse");
  mpLoadGrid("");
  document.getElementById("mp-search").value = "";
  document.getElementById("mp-folder-filter").value = "";
};

window.closeMediaPicker = function () {
  document.getElementById("media-picker-modal").classList.remove("open");
  document.body.style.overflow = "";
  _mpSelected = null;
  _mpCallback = null;
};

// ── Tab switching ──────────────────────────────────────────────────────────
window.mpSwitchTab = function (tab) {
  document.getElementById("mp-browse").style.display =
    tab === "browse" ? "flex" : "none";
  document.getElementById("mp-upload").style.display =
    tab === "upload" ? "flex" : "none";
  document
    .getElementById("mp-tab-browse")
    .classList.toggle("active", tab === "browse");
  document
    .getElementById("mp-tab-upload")
    .classList.toggle("active", tab === "upload");
};

// ── Browse tab ─────────────────────────────────────────────────────────────
window.mpSearch = function (query) {
  clearTimeout(_mpDebounce);
  _mpDebounce = setTimeout(() => mpLoadGrid(query), 250);
};

async function mpLoadGrid(query) {
  const grid = document.getElementById("mp-grid");
  const folder = document.getElementById("mp-folder-filter")?.value || "";
  grid.innerHTML =
    '<div class="mp-loading"><i class="fas fa-spinner fa-spin" style="font-size:24px"></i></div>';

  try {
    const params = new URLSearchParams();
    if (query) params.set("q", query);
    if (folder) params.set("folder", folder);

    const res = await fetch("/admin/media/search?" + params);
    const data = await res.json();

    if (!data.length) {
      grid.innerHTML = `<div class="mp-empty">
                <i class="fas fa-images" style="font-size:32px;display:block;margin-bottom:10px"></i>
                No images found.<br>
                <button onclick="mpSwitchTab('upload')" style="margin-top:12px;background:var(--teal);color:#fff;border:none;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer">
                    Upload New Image
                </button>
            </div>`;
      return;
    }

    grid.innerHTML = data
      .map(
        (m) => `
            <div class="mp-item" data-path="${m.path}" data-url="${m.url}" data-filename="${m.filename}"
                onclick="mpSelectItem(this)">
                <div class="mp-item-img">
                    <img src="${m.url}" alt="${m.alt}" loading="lazy">
                </div>
                <div class="mp-item-name">${m.filename}</div>
            </div>
        `
      )
      .join("");

    // Re-highlight previously selected item if still in grid
    if (_mpSelected) {
      const el = grid.querySelector(`[data-path="${_mpSelected.path}"]`);
      if (el) el.classList.add("selected");
    }
  } catch (e) {
    grid.innerHTML =
      '<div class="mp-empty">Failed to load. Please try again.</div>';
  }
}

window.mpSelectItem = function (el) {
  // Deselect all
  document
    .querySelectorAll("#mp-grid .mp-item")
    .forEach((i) => i.classList.remove("selected"));
  el.classList.add("selected");

  _mpSelected = {
    path: el.dataset.path,
    url: el.dataset.url,
    filename: el.dataset.filename,
  };

  const info = document.getElementById("mp-selected-info");
  const btn = document.getElementById("mp-select-btn");
  if (info) info.textContent = "✓ " + _mpSelected.filename;
  if (btn) {
    btn.disabled = false;
    btn.style.opacity = "1";
  }
};

window.mpConfirmSelect = function () {
  if (!_mpSelected || !_mpCallback) return;
  _mpCallback(_mpSelected.path, _mpSelected.url, _mpSelected.filename);
  closeMediaPicker();
};

// ── Upload tab ─────────────────────────────────────────────────────────────
window.mpHandleDrop = function (files) {
  const arr = Array.from(files);
  arr.forEach((file) => {
    if (!file.type.startsWith("image/")) return;
    const reader = new FileReader();
    reader.onload = (e) => {
      _mpQueue.push({ file, preview: e.target.result, status: "pending" });
      mpRenderQueue();
    };
    reader.readAsDataURL(file);
  });
};

function mpRenderQueue() {
  const container = document.getElementById("mp-upload-queue");
  const uploadBtn = document.getElementById("mp-upload-btn");

  if (!_mpQueue.length) {
    container.style.display = "none";
    uploadBtn.disabled = true;
    uploadBtn.style.opacity = ".5";
    return;
  }

  container.style.display = "flex";
  uploadBtn.disabled = false;
  uploadBtn.style.opacity = "1";

  container.innerHTML = _mpQueue
    .map((item, i) => {
      let overlay = "";
      if (item.status === "uploading")
        overlay =
          '<div class="mp-progress"><i class="fas fa-spinner fa-spin"></i></div>';
      if (item.status === "done")
        overlay =
          '<div class="mp-progress mp-done"><i class="fas fa-check"></i></div>';
      if (item.status === "error")
        overlay = `<div class="mp-progress mp-error">Error</div>`;
      return `<div class="mp-queue-item">
            <img src="${item.preview}" style="width:100%;height:100%;object-fit:cover">
            ${overlay}
        </div>`;
    })
    .join("");
}

window.mpClearQueue = function () {
  _mpQueue = [];
  mpRenderQueue();
  document.getElementById("mp-file-input").value = "";
  document.getElementById("mp-upload-status").textContent = "";
};

window.mpUploadAll = async function () {
  const pending = _mpQueue.filter((i) => i.status === "pending");
  if (!pending.length) return;

  const folder = document.getElementById("mp-upload-folder").value;
  const csrfEl = document.querySelector("meta[name=csrf-token]");
  const csrf = csrfEl ? csrfEl.content : "";
  const statusEl = document.getElementById("mp-upload-status");

  // Upload in batches of 5
  let done = 0,
    failed = 0;
  const lastUploaded = [];

  for (const item of pending) {
    item.status = "uploading";
    mpRenderQueue();

    const fd = new FormData();
    fd.append("files[]", item.file);
    fd.append("folder", folder);
    fd.append("_token", csrf);

    try {
      const res = await fetch("/admin/media", {
        method: "POST",
        body: fd,
        headers: { Accept: "application/json" },
      });
      const data = await res.json();

      if (data.success && data.uploaded?.length) {
        item.status = "done";
        done++;
        lastUploaded.push(...data.uploaded);
      } else {
        item.status = "error";
        failed++;
      }
    } catch (e) {
      item.status = "error";
      failed++;
    }
    mpRenderQueue();
  }

  statusEl.textContent = `${done} uploaded${
    failed ? ", " + failed + " failed" : ""
  }`;

  // Switch to browse tab and reload grid, auto-select last uploaded if only 1
  if (done > 0) {
    mpSwitchTab("browse");
    await mpLoadGrid("");

    if (lastUploaded.length === 1) {
      const el = document.querySelector(
        `#mp-grid [data-path="${lastUploaded[0].path}"]`
      );
      if (el) {
        el.scrollIntoView({ behavior: "smooth", block: "center" });
        mpSelectItem(el);
      }
    }
  }
};
