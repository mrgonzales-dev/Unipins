<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($items as $item)
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">{{ $item->name }}</h3>
                <p class="text-gray-700 dark:text-gray-300 mt-2">{{ $item->description }}</p>
                <p class="mt-4 text-sm text-gray-500">Created: {{ $item->created_at->format('M d, Y') }}</p>

                @if($item->getFirstMediaUrl('images'))
                    <img src="{{ $item->getFirstMediaUrl('images') }}" class="mt-4 rounded-lg w-full">
                @endif
            </div>
        @empty
            <p class="col-span-3 text-center text-gray-600 dark:text-gray-300">No items found.</p>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $items->withQueryString()->links() }}
    </div>
</div>
