<div class="max-w-5xl mx-auto p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">🛒 My Stores</h1>
        <button x-data @click="$dispatch('open-modal')"
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded shadow">
            + Add Store
        </button>
    </div>

    <!-- Stores List -->
    <div class="grid gap-6">
        @forelse ($ownedStores as $store)
            <div
                class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-lg p-5 flex justify-between items-start shadow">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $store->name }}</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $store->description }}</p>
                </div>
                <div class="flex flex-col gap-2 ml-4">
                    <a href="{{ route('seller.product-manager', $store->id) }}"
                        class="text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded">
                        Manage
                    </a>
                    <a href="{{ route('seller.product-manager', $store->id) }}"
                        class="text-sm bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded">
                        Settings
                    </a>
                    <button wire:click="loadStore_delete({{ $store->id }})"
                        class="text-sm bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                        Delete
                    </button>
                </div>
            </div>
        @empty
            <p class="text-gray-600 dark:text-gray-400">You don't have any stores yet.</p>
        @endforelse
    </div>


    <!-- Delete Store Modal -->
    <div x-data="{ open: false }" x-on:open-delete-store-modal.window="open = true"
        x-on:close-delete-store-modal.window="open = false" x-on:keydown.escape.window="open = false" x-show="open"
        x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
        style="display: none;">
        <!-- Modal Panel -->
        <div x-transition @click.away="open = false"
            class="w-full max-w-md h-auto max-h-[90vh] overflow-y-auto bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl p-6 sm:p-8 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">
                    Are you sure you want to Delete Store?
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
            <form wire:submit.prevent="deleteStore" class="space-y-4">
                <h2>Type the Name of the product to confirm.</h2>
                <h2>Be Careful, you cannot undo this.</h2>
                <h2>"{{ $storeName }}"</h2>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Type Product
                        Name</label>
                    <input type="text" placeholder="{{ $storeName }}" wire:model.lazy="confirmationStoreName"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-2 focus:ring-indigo-500">
                    @error('confirmationStoreName')
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



    <!-- Add Store Modal -->
    <div x-data="{ open: false }" x-on:open-modal.window="open = true" x-on:close-store-modal.window="open = false" x-on:keydown.escape.window="open = false"
        x-show="open" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;">
        <div x-transition @click.away="open = false"
            class="bg-white dark:bg-zinc-900 rounded-2xl shadow-2xl w-full max-w-lg p-6 sm:p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-zinc-900 dark:text-white">Create New Store</h2>
                <button @click="open = false" class="text-zinc-500 hover:text-zinc-900 dark:hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="createStore" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Store Name</label>
                    <input type="text" wire:model="name"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-indigo-500">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">Description</label>
                    <textarea wire:model="description" rows="3"
                        class="w-full rounded-lg border border-zinc-300 dark:border-zinc-700 bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-white p-3 focus:ring-indigo-500"></textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="open = false"
                        class="px-4 py-2 text-sm rounded-lg border border-zinc-300 dark:border-zinc-700 text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
