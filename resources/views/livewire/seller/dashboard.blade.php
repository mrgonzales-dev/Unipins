<div class="max-w-6xl mx-auto px-6 py-12 space-y-12 text-zinc-800 dark:text-zinc-100">

    <!-- Dashboard Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-5xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
            🛍️ Seller Dashboard
        </h1>
        <span class="text-sm font-medium text-zinc-500 dark:text-zinc-400">
            Logged in as {{ Auth::user()->name }}
        </span>
    </div>

    <!-- Quick Profile -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-lg">
        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-700 pb-2">
            <span class="text-zinc-500">Email</span>
                <span class="dark:text-white font-medium">{{ Auth::user()->email }}</span>
        </div>
        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-700 pb-2">
            <span class="text-zinc-500">Phone Number</span>
            <span class="dark:text-white font-medium">{{ auth()->user()->phone ?? 'Not set' }}</span>
        </div>
        <div class="flex justify-between border-b border-zinc-200 dark:border-zinc-700 pb-2">
            <span class="text-zinc-500">Role</span>
            <span class="uppercase font-semibold text-indigo-600 dark:text-indigo-400">
                {{ auth()->user()->role }}
            </span>
        </div>
    </div>

    <!-- Store Summary Section -->
    <div class="space-y-6">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">📈 Store Overview</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-1">
                <p class="text-sm text-zinc-500">Total Sales</p>
                <p class="text-2xl font-bold">₱{{ number_format($totalSales ?? 0, 2) }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-zinc-500">Pending Orders</p>
                <p class="text-2xl font-bold">{{ $pendingOrders ?? 0 }}</p>
            </div>
            <div class="space-y-1">
                <p class="text-sm text-zinc-500">Earnings This Month</p>
                <p class="text-2xl font-bold text-green-600">₱{{ number_format($monthlyEarnings ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="space-y-4">
        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">⚙️ Seller Tools</h2>
        <div class="flex flex-wrap gap-4">

            <a href="{{ route('seller.store-manager') }}" class="px-5 py-3 bg-zinc-200 dark:bg-zinc-700 rounded-xl hover:bg-zinc-300 dark:hover:bg-zinc-600 transition font-semibold">📦 View Stores </a>

           {{-- <a href="{{ route('analytics') }}" --}}
            <a href="" class="px-5 py-3 bg-zinc-200 dark:bg-zinc-700 rounded-xl hover:bg-zinc-300 dark:hover:bg-zinc-600 transition font-semibold"> 📊 Sales Analytics </a>

            {{-- <a href="{{ route('settings') }}" --}}
            <a href="" class="px-5 py-3 bg-zinc-200 dark:bg-zinc-700 rounded-xl hover:bg-zinc-300 dark:hover:bg-zinc-600 transition font-semibold"> ⚙️ Store Settings </a>
        </div>
    </div>

</div>
