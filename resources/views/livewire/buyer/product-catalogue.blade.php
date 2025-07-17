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
                        <a href="#"  wire:click.prevent="loadSingleProductView({{ $product->id }})" class="line-clamp-1">
                            <h2 class="text-lg font-semibold text-zinc-900 dark:text-white line-clamp-1">
                                {{ $product->name }}
                            </h2>
                        </a>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-2">
                            {{ $product->description }}
                        </p>
                        <div class="flex justify-between items-center mt-4 text-sm">
                            <span class="text-indigo-600 font-semibold">₱{{ number_format($product->price, 2) }}</span>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ $product->stock }} left</span>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button
                                wire:click="addToCart({{ $product->id }})"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg shadow">
                                Buy
                            </button>
                            <button
                                wire:click="addToCart({{ $product->id }})"
                                class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg shadow">
                                Add to Cart
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


    {{-- Viewing Single Product Modal --}}
    <div x-data="{ open: false, currentSlide: 0 }"
         x-on:open-view-product-modal.window="open = true"
         x-on:keydown.escape.window="open = false"
         x-show="open"
         x-transition.opacity
         class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
         style="display: none;">

        <div x-transition
             @click.away="open = false"
             class="w-full max-w-4xl mx-auto bg-white dark:bg-zinc-900 rounded-3xl shadow-2xl p-6 sm:p-10 relative overflow-hidden space-y-6">

            <!-- Close Button -->
            <button @click="open = false"
                class="absolute top-4 right-4 text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Product Display -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">

                <!-- Image V-Carousel -->
                <div class="relative w-full h-full" x-data="{ currentSlide: 0 }">
                    <div class="relative overflow-hidden rounded-2xl border border-zinc-200 dark:border-zinc-700">
                    @foreach ($current_image as $index => $image)
                            <img x-show="currentSlide === {{ $index }}"
                                    src="{{ $image->getUrl() }}"
                                    class="w-full h-96 object-cover transition-all duration-500 ease-in-out"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100" />
                    @endforeach
                </div>

                    <!-- Controls -->
                    @if (count($current_image) > 1)
                    <template x-if="[1, 2, 3].length > 1">
                        <div class="absolute inset-0 flex justify-between items-center px-4">
                            <button class="bg-black/50 text-white p-2 rounded-full"
                                @click="currentSlide = (currentSlide === 0) ? {{ count($current_image) - 1 }} : currentSlide - 1">
                                ‹
                            </button>

                            <button class="bg-black/50 text-white p-2 rounded-full"
                                @click="currentSlide = (currentSlide === {{ count($current_image) - 1 }}) ? 0 : currentSlide + 1">
                                ›
                            </button>
                        </div>
                    </template>
                    @endif

                    <!-- Dots -->
                    <div class="flex justify-center mt-3 space-x-2">
                        @foreach ($current_image as $index => $image)
                            <button class="w-3 h-3 rounded-full"
                            :class="{ 'bg-blue-600': currentSlide === {{ $index }}, 'bg-gray-400': currentSlide !== {{ $index }} }"
                                @click="currentSlide = {{$index}} "></button>
                        @endforeach
                    </div>
                </div>

                <!-- Product Info -->
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">{{$productName}}</h2>
                    <p class="text-zinc-600 dark:text-zinc-400 leading-relaxed">
                        {{$productDescription}}
                    </p>
                    <div class="flex items-center justify-between pt-4">
                        <span class="text-2xl font-semibold text-indigo-600 dark:text-indigo-400">
                            ₱{{ $productPrice }}
                        </span>
                        <span class="text-sm text-zinc-500 dark:text-zinc-400">
                            {{ $productStock }} in stock
                        </span>
                    </div>

                    <!-- Tags or Categories (Optional) -->
                    <div class="flex flex-wrap gap-2 pt-2">
                        <span class="text-xs px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 dark:bg-indigo-800 dark:text-white">
                            Category 1
                        </span>
                        <span class="text-xs px-3 py-1 rounded-full bg-green-100 text-green-700 dark:bg-green-800 dark:text-white">
                            Bestseller
                        </span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-700 pt-6">
                <button @click="open = false"
                    class="px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                    Close
                </button>
            </div>
        </div>
    </div>


</div>
