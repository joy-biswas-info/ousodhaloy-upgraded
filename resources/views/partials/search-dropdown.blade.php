<div x-show="open && results.length" x-cloak class="search-results-panel">
    <template x-for="p in results" :key="p.id">
        <a :href="'/shop/product/' + p.slug" class="search-result-item">
            <div class="search-result-thumb">
                <img x-show="p.thumbnail_url" :src="p.thumbnail_url">
                <span x-show="!p.thumbnail_url" style="font-size:20px">💊</span>
            </div>
            <div class="search-result-info">
                <p x-text="p.name"></p>
                <p x-text="(p.generic_name||'')+(p.brand?' · '+p.brand:'')"></p>
            </div>
            <div class="search-result-price">
                <p class="now">৳<span x-text="p.price"></span></p>
                <p x-show="p.discount>0" class="discount">-<span x-text="p.discount"></span>%</p>
            </div>
        </a>
    </template>
    <div class="search-view-all">
        <button @click="goToShop()">
            View all for "<span x-text="query"></span>" →
        </button>
    </div>
</div>
