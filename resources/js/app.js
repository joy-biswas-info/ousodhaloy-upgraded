// Alpine.js loaded via CDN

window.addToCart = function (productId, qty = 1, btn = null) {
  // Find the button if not passed directly
  if (!btn) {
    btn = event?.currentTarget || null;
  }

  // Loading state
  if (btn) {
    btn.disabled = true;
    btn._originalHTML = btn.innerHTML;
    btn.innerHTML =
      '<i class="fas fa-spinner fa-spin" style="font-size:11px"></i>';
  }

  fetch("/cart/add", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]")?.content,
      Accept: "application/json",
    },
    body: JSON.stringify({ product_id: productId, qty }),
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        // Update all cart count badges
        const count = data.count;
        document.querySelectorAll("#cart-count").forEach((el) => {
          el.textContent = count;
          el.style.display = count > 0 ? "flex" : "none";
        });
        // Mobile bottom nav badge
        document
          .querySelectorAll(".mobile-bottom-nav .cart-btn .badge")
          .forEach((el) => {
            el.textContent = count;
            el.style.display = count > 0 ? "flex" : "none";
          });

        // Button success state
        if (btn) {
          btn.innerHTML =
            '<i class="fas fa-check" style="font-size:11px"></i> Added';
          btn.style.background = "var(--teal)";
          btn.style.color = "#fff";
          // Reset after 2 seconds
          setTimeout(() => {
            btn.innerHTML = btn._originalHTML;
            btn.style.background = "";
            btn.style.color = "";
            btn.disabled = false;
          }, 2000);
        }

        showToast(data.message || "Added to cart!", "success");
      } else {
        if (btn) {
          btn.innerHTML = btn._originalHTML;
          btn.disabled = false;
        }
        showToast(data.message || "Could not add to cart", "error");
      }
    })
    .catch(() => {
      if (btn) {
        btn.innerHTML = btn._originalHTML;
        btn.disabled = false;
      }
      showToast("Network error — please try again", "error");
    });
};

window.showToast = function (msg, type = "success") {
  document.querySelectorAll(".toast-msg").forEach((el) => el.remove());
  const el = document.createElement("div");
  el.className = "toast-msg";
  el.style.cssText = [
    "position:fixed",
    "bottom:72px",
    "left:50%",
    "transform:translateX(-50%)",
    "z-index:9999",
    `background:${type === "success" ? "var(--teal, #0e7673)" : "#dc2626"}`,
    "color:#fff",
    "padding:10px 20px",
    "border-radius:25px",
    "font-size:13px",
    "font-weight:600",
    "white-space:nowrap",
    "box-shadow:0 4px 20px rgba(0,0,0,.2)",
    "display:flex",
    "align-items:center",
    "gap:8px",
  ].join(";");
  el.innerHTML = `<i class="fas fa-${
    type === "success" ? "check" : "exclamation"
  }-circle"></i>${msg}`;
  document.body.appendChild(el);
  // Slide in
  el.animate(
    [
      { opacity: 0, transform: "translateX(-50%) translateY(10px)" },
      { opacity: 1, transform: "translateX(-50%) translateY(0)" },
    ],
    { duration: 200, fill: "forwards" }
  );
  setTimeout(() => {
    el.animate([{ opacity: 1 }, { opacity: 0 }], {
      duration: 200,
      fill: "forwards",
    }).onfinish = () => el.remove();
  }, 2500);
};
