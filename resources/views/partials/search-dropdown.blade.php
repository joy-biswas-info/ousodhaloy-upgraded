<div x-show="open && results.length" x-cloak
    style="position:absolute;top:calc(100% + 6px);left:0;right:0;background:#fff;border-radius:12px;box-shadow:0 8px 32px rgba(0,0,0,.15);border:1px solid #e5e7eb;z-index:500;max-height:380px;overflow-y:auto;">
    <template x-for="p in results" :key="p . id">
        <a :href="'/shop/product/' + p . slug"
            style="display:flex;align-items:center;gap:12px;padding:12px 14px;border-bottom:1px solid #f3f4f6;text-decoration:none;"
            @mouseenter="$el.style.background='#f9fafb'" @mouseleave="$el.style.background=''">
            <div
                style="width:40px;height:40px;background:#f8fafb;border-radius:8px;flex-shrink:0;overflow:hidden;display:flex;align-items:center;justify-content:center;">
                <img x-show="p.thumbnail_url" :src="p . thumbnail_url" style="width:100%;height:100%;object-fit:contain;">
                <span x-show="!p.thumbnail_url" style="font-size:20px">💊</span>
            </div>
            <div style="flex:1;min-width:0">
                <p style="font-size:13px;font-weight:600;color:#1f2937;overflow:hidden;text-overflow:ellipsis;white-space:nowrap"
                    x-text="p.name"></p>
                <p style="font-size:11px;color:#6b7280" x-text="(p.generic_name||'')+(p.brand?' · '+p.brand:'')"></p>
            </div>
            <div style="text-align:right;flex-shrink:0">
                <p style="font-size:13px;font-weight:800;color:var(--teal)">৳<span x-text="p.price"></span></p>
                <p x-show="p.discount>0" style="font-size:11px;color:#dc2626;font-weight:600">-<span
                        x-text="p.discount"></span>%</p>
            </div>
        </a>
    </template>
    <div style="padding:10px;text-align:center;background:#f9fafb">
        <button @click="goToShop()"
            style="font-size:12px;color:var(--teal);font-weight:600;background:none;border:none;cursor:pointer">
            View all for "<span x-text="query"></span>" →
        </button>
    </div>
</div>