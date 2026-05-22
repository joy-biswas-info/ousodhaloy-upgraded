// Admin JS — loaded on all admin pages

// Confirm dangerous actions
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
});

// Auto-dismiss flash messages after 4s
document.querySelectorAll('.flash-msg').forEach(el => {
    setTimeout(() => el.remove(), 4000);
});

// Select all checkboxes
const selectAll = document.getElementById('select-all');
if (selectAll) {
    selectAll.addEventListener('change', () => {
        document.querySelectorAll('.order-cb, .product-cb').forEach(cb => {
            cb.checked = selectAll.checked;
        });
    });
}

// Image preview on file input
document.querySelectorAll('input[type=file][data-preview]').forEach(input => {
    input.addEventListener('change', () => {
        const previewId = input.dataset.preview;
        const preview = document.getElementById(previewId);
        if (preview && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    });
});

// Slug auto-gen from title
const nameInput = document.querySelector('[data-slug-source]');
const slugInput = document.querySelector('[data-slug-target]');
if (nameInput && slugInput) {
    let slugEdited = slugInput.value !== '';
    slugInput.addEventListener('input', () => { slugEdited = true; });
    nameInput.addEventListener('input', () => {
        if (!slugEdited) {
            slugInput.value = nameInput.value
                .toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
}
