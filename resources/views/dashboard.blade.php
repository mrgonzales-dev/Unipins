<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Store') }}
            </h2>

            <div class="flex items-center">
                <!-- Search bar -->
                <form method="GET" action="{{ route('items.index') }}" class="w-full max-w-xs mr-4">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search items..."
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    >
                </form>

                @if(auth()->user()->can('create items'))

                    <!-- Create Item button -->
                    <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        {{ __('Create Item') }}
                    </a>

                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <!-- Import items\index.blade.php -->
        @include('items.index')
    </div>
</x-app-layout>
