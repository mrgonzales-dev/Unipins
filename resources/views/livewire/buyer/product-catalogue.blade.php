<div class="max-w-6xl mx-auto p-6 space-y-6">
    <!-- Header & Search -->
    <div class="flex flex-col sm:flex-row justify-between gap-4 items-center">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
            🛍️ {{ $store?->name ?? 'All Products' }}
        </h1>

        <div class="w-full sm:w-80">
            <input
                wire:model.debounce.300ms="query"
                type="text"
                placeholder="Search products…"
                class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800
                       text-sm p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            >
        </div>
    </div>

    <!-- Product Grid -->
    @if ($products->count() > 0)
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div
                    class="bg-white hover:scale-105 transition duration-300 border border-zinc-200 dark:border-zinc-700
                           dark:bg-zinc-800 rounded-xl shadow-md overflow-hidden">

                    <!-- Image carousel (Alpine) -->
                    <div x-data="{ currentSlide: 0 }" class="relative w-full max-w-xl mx-auto">
                        <div class="relative overflow-hidden rounded-xl">
                            @foreach ($product->getMedia('product_images') as $index => $image)
                                <img
                                    x-show="currentSlide === {{ $index }}"
                                    src="{{ $image->getUrl() }}"
                                    alt="{{ $product->name }} image {{ $index + 1 }}"
                                    class="w-full h-64 object-cover transition-all duration-500 ease-in-out"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100">
                            @endforeach
                        </div>

                        @if ($product->getMedia('product_images')->count() > 1)
                            <div class="absolute inset-0 flex justify-between items-center px-4">
                                <button class="bg-black/50 text-white p-2 rounded-full"
                                        @click="currentSlide = (currentSlide === 0)
                                                ? {{ $product->getMedia('product_images')->count() - 1 }}
                                                : currentSlide - 1">
                                    ‹
                                </button>
                                <button class="bg-black/50 text-white p-2 rounded-full"
                                        @click="currentSlide = (currentSlide ===
                                                {{ $product->getMedia('product_images')->count() - 1 }})
                                                ? 0 : currentSlide + 1">
                                    ›
                                </button>
                            </div>
                        @endif

                        <div class="flex justify-center mt-2 space-x-2">
                            @foreach ($product->getMedia('product_images') as $index => $image)
                                <button
                                    class="w-3 h-3 rounded-full"
                                    :class="{ 'bg-blue-600': currentSlide === {{ $index }},
                                              'bg-gray-400': currentSlide !== {{ $index }} }"
                                    @click="currentSlide = {{ $index }}">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="p-4 space-y-2">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white line-clamp-1">
                            {{ $product->name }}
                        </h2>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2">
                            {{ $product->description }}
                        </p>
                        <div class="flex justify-between items-center mt-4 text-sm">
                            <span class="text-indigo-600 font-semibold">₱{{ number_format($product->price, 2) }}</span>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ $product->stock }} left</span>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button
                                wire:click="loadViewProduct({{ $product->id }})"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                                View Details
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-zinc-600 dark:text-zinc-400 mt-12">
            <p class="text-lg">No products found.</p>
        </div>
    @endif

</div>
