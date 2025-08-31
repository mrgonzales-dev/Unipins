<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Store') }}
            </h2>

            <!-- Search bar -->
            <form method="GET" action="{{ route('items.index') }}" class="w-1/3">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search items..."
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                >
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <!-- Import items\index.blade.php -->
        @include('items.index')
    </div>
</x-app-layout>
