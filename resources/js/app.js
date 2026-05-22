import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

// Cart functions (global)
window.addToCart = function (productId, qty = 1) {
  fetch("/cart/add", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      "X-Requested-With": "XMLHttpRequest",
      "X-CSRF-TOKEN": document.querySelector("meta[name=csrf-token]").content,
    },
    body: JSON.stringify({ product_id: productId, qty }),
  })
    .then((r) => r.json())
    .then((data) => {
      if (data.success) {
        const count = document.getElementById("cart-count");
        if (count) {
          count.textContent = data.count;
          count.classList.remove("hidden");
        }
        showToast(data.message || "Added to cart!", "success");
      } else {
        showToast(data.message || "Error adding to cart", "error");
      }
    })
    .catch(() => showToast("Network error", "error"));
};

window.showToast = function (msg, type = "success") {
  const existing = document.querySelectorAll(".toast-msg");
  existing.forEach((el) => el.remove());

  const el = document.createElement("div");
  el.className = `toast-msg fixed bottom-5 right-5 z-[9999] px-5 py-3 rounded-xl shadow-xl text-white text-sm font-semibold flex items-center gap-2 transition-all`;
  el.style.background =
    type === "success" ? "#0e7673" : type === "error" ? "#dc2626" : "#2563eb";
  el.innerHTML = `<i class="fas fa-${
    type === "success" ? "check" : "exclamation"
  }-circle"></i>${msg}`;
  document.body.appendChild(el);
  setTimeout(() => el.remove(), 3500);
};
