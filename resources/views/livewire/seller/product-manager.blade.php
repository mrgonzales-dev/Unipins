<div class="max-w-6xl mx-auto p-6 space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-zinc-900 dark:text-white">
            🧺 {{ $store->name }} – Products
        </h1>
        <button
            x-data
            @click="$dispatch('open-product-modal')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow"
        >
            + Add Product
        </button>
    </div>

    <!-- Product List -->
    @if($products->count())
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($products as $product)
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md overflow-hidden">
                    <div class="p-4 space-y-2">
                        <h2 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $product->name }}</h2>
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 line-clamp-3">{{ $product->description }}</p>
                        <div class="flex justify-between items-center mt-4 text-sm">
                            <span class="text-indigo-600 font-semibold">₱{{ number_format($product->price, 2) }}</span>
                            <span class="text-zinc-500 dark:text-zinc-400">{{ $product->stock }} in stock</span>
                        </div>
                        <div class="flex justify-end gap-2 mt-4">
                            <button wire:click="editProduct({{ $product->id }})" class="text-sm text-blue-500 hover:underline">Edit</button>
                            <button wire:click="deleteProduct({{ $product->id }})" class="text-sm text-red-500 hover:underline">Delete</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-zinc-600 dark:text-zinc-400 mt-12">
            <p class="text-lg">No products found in this store.</p>
            <button
                x-data
                @click="$dispatch('open-product-modal')"
                class="mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg transition"
            >
                + Add Your First Product
            </button>
        </div>
    @endif

    <!-- Add Product Modal -->
    <div
        x-data="{ open: false }"
        x-on:open-product-modal.window="open = true"
        x-on:keydown.escape.window="open = false"
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        style="display: none;"
    >
        <!-- Modal Panel -->
        <div
            x-transition
            @click.away="open = false"
            class="w-full max-w-md h-auto max-h-[90vh] overflow-y-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-6 sm:p-8 space-y-6"
        >
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                    Add New Product
                </h2>
                <button @click="open = false" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="createProduct" class="space-y-4">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Product Name</label>
                    <input type="text" wire:model="productName"
                           class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @error('productName') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
                    <textarea wire:model="productDescription" rows="3"
                              class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    @error('productDescription') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                </div>

                <!-- Price and Stock -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Price (₱)</label>
                        <input type="number" step="0.01" wire:model="productPrice"
                               class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('productPrice') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Stock</label>
                        <input type="number" wire:model="productStock"
                               class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @error('productStock') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button"
                            @click="open = false"
                            class="px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        Cancel
                    </button>
                    <button type="submit"
                            @click="open = false"
                            class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                        Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
