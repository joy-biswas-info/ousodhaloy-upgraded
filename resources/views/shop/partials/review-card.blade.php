{{-- ── REVIEWS ── --}}
<div x-data="{ openReviews: false }"
    class="bg-white rounded-2xl border mb-5 shadow-sm overflow-hidden">

    {{-- Header --}}
    <button type="button"
        @click="openReviews = !openReviews"
        class="w-full flex items-center justify-between p-5 text-left">

        <div>
            <h2 class="font-black text-gray-800 text-lg">
                Customer Reviews
                @if ($product->rating_count > 0)
                    <span class="text-sm font-normal text-gray-400 ml-1">
                        {{-- ({{ $product->rating_count }}) --}}
                        200+
                    </span>
                @endif
            </h2>
        </div>

        <div class="flex items-center gap-3">

            @if ($product->rating_count > 0)
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-black"
                        style="color:var(--teal)">
                        {{ $product->average_rating }}
                    </span>

                    <div class="flex flex-col gap-0.5">
                        <div class="flex gap-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                <i
                                    class="fas fa-star text-xs {{ $i <= round($product->average_rating) ? 'text-amber-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>

                        <span class="text-xs text-gray-400">
                            out of 5
                        </span>
                    </div>
                </div>
            @endif

            <i class="fas fa-chevron-down text-gray-400 transition-transform duration-300"
                :class="{ 'rotate-180': openReviews }"></i>

        </div>
    </button>

    {{-- Content --}}
    <div x-show="openReviews"
        x-collapse
        x-cloak>

        <div class="px-5 pb-5">

            @forelse($product->reviews as $review)
                <div class="border-b border-teal-50 last:border-0 py-4">

                    <div class="flex items-start gap-3">

                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                            style="background:var(--teal)">
                            {{ strtoupper(substr($review->display_name, 0, 1)) }}
                        </div>

                        <div class="flex-1 min-w-0">

                            <div class="flex items-center justify-between gap-2 flex-wrap">

                                <div>
                                    <span class="text-sm font-bold text-gray-800">
                                        {{ $review->display_name }}
                                    </span>

                                    <div class="flex gap-0.5 mt-0.5">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <i
                                                class="fas fa-star text-[10px] {{ $i <= $review->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                </div>

                                <span class="text-xs text-gray-400">
                                    {{ $review->created_at->format('d M Y') }}
                                </span>

                            </div>

                            @if ($review->title)
                                <p class="text-sm font-semibold text-gray-700 mt-1">
                                    {{ $review->title }}
                                </p>
                            @endif

                            @if ($review->body)
                                <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                    {{ $review->body }}
                                </p>
                            @endif

                        </div>

                    </div>

                </div>
            @empty

                <div class="py-6 text-center">
                    <div class="text-xl mb-1">⭐</div>

                    <p class="text-sm font-semibold text-gray-700">
                        No reviews yet
                    </p>

                    <p class="text-xs text-gray-400 mt-1">
                        Be the first to review this product
                    </p>
                </div>

            @endforelse

            {{-- Write Review --}}
            <div class="pt-5 mt-2">

                @auth

                    <h3 class="font-bold text-gray-800 text-sm mb-4">
                        Write a Review
                    </h3>

                    @if (session('review_success'))
                        <div
                            class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-4">
                            <i class="fas fa-check-circle mr-1.5"></i>
                            {{ session('review_success') }}
                        </div>
                    @endif

                    <form method="POST"
                        action="{{ route('shop.product.review', $product->slug) }}"
                        class="space-y-4">

                        @csrf

                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-2">
                                Your Rating *
                            </label>

                            <div class="flex gap-1"
                                x-data="{ rating: {{ old('rating', 0) }}, hover: 0 }">

                                @for ($s = 1; $s <= 5; $s++)
                                    <button type="button"
                                        @click="rating = {{ $s }}"
                                        @mouseenter="hover = {{ $s }}"
                                        @mouseleave="hover = 0"
                                        class="text-2xl leading-none focus:outline-none">

                                        <i class="fas fa-star"
                                            :class="(hover || rating) >= {{ $s }}
                                                ? 'text-amber-400'
                                                : 'text-gray-200'"></i>

                                    </button>
                                @endfor

                                <input type="hidden"
                                    name="rating"
                                    :value="rating">
                            </div>

                            @error('rating')
                                <p class="text-red-500 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-1">
                                Review Title
                            </label>

                            <input type="text"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="Summarise your experience"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-50">

                            @error('title')
                                <p class="text-red-500 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold text-gray-600 block mb-1">
                                Your Review *
                            </label>

                            <textarea name="body"
                                rows="3"
                                required
                                placeholder="Share your experience with this product..."
                                class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm outline-none focus:border-teal-400 focus:ring-2 focus:ring-teal-50 resize-none">{{ old('body') }}</textarea>

                            @error('body')
                                <p class="text-red-500 text-xs mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <button type="submit"
                            class="py-2.5 px-6 rounded-xl text-white text-sm font-bold transition-all active:scale-95"
                            style="background:var(--teal)">

                            <i class="fas fa-paper-plane mr-1.5"></i>
                            Submit Review

                        </button>

                    </form>

                @else

                    <div class="bg-gray-50 rounded-xl p-4 text-center">

                        <p class="text-sm text-gray-600 mb-3">
                            Sign in to leave a review
                        </p>

                        <a href="{{ route('auth.login') }}"
                            class="inline-flex items-center gap-2 py-2 px-5 rounded-xl text-white text-sm font-bold"
                            style="background:var(--teal)">

                            <i class="fas fa-sign-in-alt"></i>
                            Login to Review

                        </a>

                    </div>

                @endauth

            </div>

        </div>

    </div>

</div>