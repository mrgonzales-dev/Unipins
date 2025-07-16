<div class="max-w-6xl mx-auto p-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between gap-2 items-center">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
            🧺 {{ $store->name }} – Products
        </h1>
        <div class="flex gap-2">
            <button x-data wire:click="openAddProductModal"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                + Add Product
            </button>


            <!-- // Import Products -->
            <button x-data wire:click="openImportProductModal"
                class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg shadow">
                Import Products
            </button>

            <!-- // Export Products -->
            <button wire:click="exportProducts"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow">
                Export Products
            </button>

            <!-- //button to go back to store manager -->
            <a href="{{ route('seller.store-manager') }}" class="bg-gray-700 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">
                Go Back
            </a>
        </div>
    </div>

    <!-- Product List -->
    @if ($products->count())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div
                    class="bg-white hover:scale-105 transition duration-300 border border-zinc-200 dark:border-zinc-700 dark:bg-zinc-800 rounded-xl shadow-md overflow-hidden">
                    <!-- list down all the media available for the product using getMedia as thumbnails-->
                    <div x-data="{ currentSlide: 0 }" class="relative w-full max-w-xl mx-auto">
                        <!-- Slides -->
                        <div class="relative overflow-hidden rounded-xl">
                            @foreach ($product->getMedia('product_images') as $index => $image)
                                <img
                                    x-show="currentSlide === {{ $index }}"
                                    src="{{ $image->getUrl() }}"
                                    alt="Product Image {{ $index + 1 }}"
                                    class="w-full h-64 object-cover transition-all duration-500 ease-in-out"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 transform scale-95"
                                    x-transition:enter-end="opacity-100 transform scale-100"
                                >
                            @endforeach
                        </div>
                       @if ($product->getMedia('product_images')->count() > 1)
                            <!-- Controls -->
                            <div class="absolute inset-0 flex justify-between items-center px-4">
                                <button
                                    class="bg-black/50 text-white p-2 rounded-full"
                                    @click="currentSlide = (currentSlide === 0) ? {{ $product->getMedia('product_images')->count() - 1 }} : currentSlide - 1"
                                >
                                    ‹
                                </button>
                                <button
                                    class="bg-black/50 text-white p-2 rounded-full"
                                    @click="currentSlide = (currentSlide === {{ $product->getMedia('product_images')->count() - 1 }}) ? 0 : currentSlide + 1"
                                >
                                    ›
                                </button>
                            </div>
                        @endif
                        <!-- Dots -->
                        <div class="flex justify-center mt-2 space-x-2">
                            @foreach ($product->getMedia('product_images') as $index => $image)
                                <button
                                    class="w-3 h-3 rounded-full"
                                    :class="{ 'bg-blue-600': currentSlide === {{ $index }}, 'bg-gray-400': currentSlide !== {{ $index }} }"
                                    @click="currentSlide = {{ $index }}">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="p-4 space-y-2">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $product->name }}</h2>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-3">{{ $product->description }}</p>
                        <div class="flex justify-between items-center mt-4 text-sm">
                            <span class="text-indigo-600 font-semibold">₱{{ number_format($product->price, 2) }}</span>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ $product->stock }} in stock</span>
                        </div>
                        <div class="flex justify-end gap-2 mt-4">

                            <!-- //view button (main button) -->
                            <button x-data="{ open: false }"
                                wire:click="loadViewProduct({{ $product->id }})"
                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow">
                                View Product
                            </button>

                            <button wire:click="loadProduct({{ $product->id }})"
                                class="text-sm text-zinc-500 hover:underline">Edit</button>
                            <button wire:click="loadProduct_delete({{ $product->id }})"
                                class="text-sm text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-zinc-600 dark:text-zinc-400 mt-12">
            <p class="text-lg">No products found in this store.</p>
            <button x-data @click="$dispatch('open-product-modal')"
                class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg transition">
                + Add Your First Product
            </button>
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

                    {{-- <!-- Controls --> --}}
                    {{-- @if (count($current_image) > 1) --}}
                    {{--     <div class="absolute inset-0 flex justify-between items-center px-4"> --}}
                    {{--         <a --}}
                    {{--             class="bg-black/50 text-white p-2 rounded-full" --}}
                    {{--             @click="currentSlide = (currentSlide === 0) ? {{ count($current_image) - 1 }} : currentSlide - 1" --}}
                    {{--         > --}}
                    {{--             ‹ --}}
                    {{--         </a> --}}
                    {{--         <a --}}
                    {{--             class="bg-black/50 text-white p-2 rounded-full" --}}
                    {{--             @click="currentSlide = (currentSlide === {{ count($current_image) - 1 }}) ? 0 : currentSlide + 1" --}}
                    {{--         > --}}
                    {{--             › --}}
                    {{--         </a> --}}
                    {{--     </div> --}}
                    {{-- @endif --}}
                    {{-- <!-- Dots --> --}}
                    {{-- <div class="flex justify-center mt-2 space-x-2"> --}}
                    {{--     @foreach ($current_image as $index => $image) --}}
                    {{--         <button --}}
                    {{--             class="w-3 h-3 rounded-full" --}}
                    {{--             :class="{ 'bg-blue-600': currentSlide === {{ $index }}, 'bg-gray-400': currentSlide !== {{ $index }} }" --}}
                    {{--             @click="currentSlide = {{ $index }}"> --}}
                    {{--         </button> --}}
                    {{--     @endforeach --}}
                    {{-- </div> --}}

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
                                @click="currentSlide = (currentSlide === 0) ? 2 : currentSlide - 1">
                                ‹
                            </button>
                            <button class="bg-black/50 text-white p-2 rounded-full"
                                @click="currentSlide = (currentSlide === 2) ? 0 : currentSlide + 1">
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
                <button
                wire:click="loadProduct({{$productId}})"
                class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                  Edit Product
                </button>
            </div>
        </div>
    </div>


    <!-- Delete Modal -->
    <div x-data="{ open: false }" x-on:open-delete-product-modal.window="open = true"
        x-on:close-delete-product-modal.window="open = false" x-on:keydown.escape.window="open = false" x-show="open"
        x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        style="display: none;">
        <!-- Modal Panel -->
        <div x-transition @click.away="open = false"
            class="w-full max-w-md h-auto max-h-[90vh] overflow-y-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-6 sm:p-8 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                    Are you sure you want to Delete Product?
                </h2>

                <button @click="open = false" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="deleteProduct" class="space-y-4">
                <h2>Type the Name of the product to confirm.</h2>
                <h2>"{{ $productName }}"</h2>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Type Product
                        Name</label>
                    <input type="text" placeholder="{{ $productName }}" wire:model.lazy="confirmationName"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500">
                    @error('confirmationName')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                    <button type="submit"
                        class="mt-4 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg transition disabled:cursor-not-allowed">
                        Delete Product
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Edit Product Modal -->
    <div x-data="{ open: false }" @close-product-modal.window="reset()" x-on:open-edit-product-modal.window="open = true"
        x-on:keydown.escape.window="open = false" x-show="open" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <!-- Modal Panel -->
        <div x-transition @click.away="open = false"
            class="w-full max-w-md h-auto max-h-[90vh] overflow-y-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-6 sm:p-8 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                    Edit Product
                </h2>
                <button @click="open = false" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="updateProduct" class="space-y-4">

                <!-- list down all the media available for the product using getMedia as thumbnails-->
                <div x-data="{ currentSlide: 0 }" class="relative w-full max-w-xl mx-auto">
                    <!-- Slides -->
                    <div class="relative overflow-hidden rounded-xl">
                        {{-- @foreach ($product->getMedia('product_images') as $index => $image) --}}
                        @foreach ($current_image as $index => $image)
                            <img
                                x-show="currentSlide === {{ $index }}"
                                src="{{ $image->getUrl() }}"
                                alt="Product Image {{ $index + 1 }}"
                                class="w-full h-64 object-cover transition-all duration-500 ease-in-out"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                            >
                        @endforeach
                    </div>
                    <!-- Controls -->
                    @if (count($current_image) > 1)
                        <div class="absolute inset-0 flex justify-between items-center px-4">
                            <a
                                class="bg-black/50 text-white p-2 rounded-full"
                                @click="currentSlide = (currentSlide === 0) ? {{ count($current_image) - 1 }} : currentSlide - 1"
                            >
                                ‹
                            </a>
                            <a
                                class="bg-black/50 text-white p-2 rounded-full"
                                @click="currentSlide = (currentSlide === {{ count($current_image) - 1 }}) ? 0 : currentSlide + 1"
                            >
                                ›
                            </a>
                        </div>
                    @endif
                    <!-- Dots -->
                    <div class="flex justify-center mt-2 space-x-2">
                        @foreach ($current_image as $index => $image)
                            <button
                                class="w-3 h-3 rounded-full"
                                :class="{ 'bg-blue-600': currentSlide === {{ $index }}, 'bg-gray-400': currentSlide !== {{ $index }} }"
                                @click="currentSlide = {{ $index }}">
                            </button>
                        @endforeach
                    </div>
                </div>


                <!-- Image Input-->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">New Product Image</label>
                    <input type="file" wire:model="newImage" multiple
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500">
                    @error('newImage')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Product Name</label>
                    <input type="text" wire:model="productName"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500">
                    @error('productName')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
                    <textarea wire:model="productDescription" rows="3"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500"></textarea>
                    @error('productDescription')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Price (₱)</label>
                        <input type="number" step="0.01" wire:model="productPrice"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500">
                        @error('productPrice')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Stock</label>
                        <input type="number" wire:model="productStock"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500">
                        @error('productStock')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        Cancel
                    </button>
                    <button type="submit" @click="open = false"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Import Modal -->
    <div x-data="{ open: false }" x-on:open-import-product-modal.window="open = true"
        x-on:close-import-product-modal.window="open = false" x-on:keydown.escape.window="open = false" x-show="open"
        x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        style="display: none;">
        <!-- Modal Panel -->
        <div x-transition @click.away="open = false"
            class="w-full max-w-md h-auto max-h-[90vh] overflow-y-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-6 sm:p-8 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                    Import Products
                </h2>
                <button @click="open = false" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="importProducts" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Select
                            File</label>
                        <input type="file" name="importFile" wire:model="importFile"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500">
                        @error('importFile')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        Cancel
                    </button>
                    <button type="submit" @click="open = false"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>




    <!-- Add Product Modal -->
    <div x-data="{ open: false }" x-on:open-product-modal.window="open = true"
        x-on:keydown.escape.window="open = false" x-show="open" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        style="display: none;">
        <!-- Modal Panel -->
        <div x-transition @click.away="open = false"
            class="w-full max-w-md h-auto max-h-[90vh] overflow-y-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-6 sm:p-8 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                    Add New Product
                </h2>
                <button @click="open = false" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="createProduct" class="space-y-4">
                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Product
                        Image</label>
                    <input type="file" wire:model="productImages" multiple
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500">
                    @error('productImage')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>


                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Product Name</label>
                    <input type="text" wire:model="productName" placeholder="Product Name"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('productName')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
                    <textarea wire:model="productDescription" rows="3" placeholder="Product Description"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    @error('productDescription')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Price
                            (₱)</label>
                        <input type="number" step="0.01" wire:model="productPrice" placeholder="Product Price"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('productPrice')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Stock</label>
                        <input type="number" wire:model="productStock" placeholder="Product Stock"
                            class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('productStock')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        Cancel
                    </button>
                    <button type="submit" @click="open = false"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
