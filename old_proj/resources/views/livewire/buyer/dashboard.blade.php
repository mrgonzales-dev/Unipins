<div class="max-w-4xl mx-auto px-6 py-12 space-y-10 text-zinc-800 dark:text-zinc-100">

    <h1 class="text-5xl font-extrabold text-zinc-900 dark:text-white tracking-tight">
        Dashboard
    </h1>

    <div class="space-y-6 text-lg">
        <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-700 pb-2">
            <span class="text-zinc-500 dark:text-zinc-400">Name</span>
            <span class="font-medium">{{ auth()->user()->name }}</span>
        </div>

        <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-700 pb-2">
            <span class="text-zinc-500 dark:text-zinc-400">Email</span>
            <span class="font-medium">{{ auth()->user()->email }}</span>
        </div>

        <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-700 pb-2">
            <span class="text-zinc-500 dark:text-zinc-400">Phone Number</span>
            <span class="font-medium">{{ auth()->user()->phone_number ?? 'Not set' }}</span>
        </div>

        <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-700 pb-2">
            <span class="text-zinc-500 dark:text-zinc-400">Address</span>
            <span class="font-medium">{{ auth()->user()->address ?? 'Not set' }}</span>
        </div>

        <div class="flex justify-between items-center border-b border-zinc-200 dark:border-zinc-700 pb-2">
            <span class="text-zinc-500 dark:text-zinc-400">Role</span>
            <span class="font-bold uppercase tracking-wide text-indigo-600 dark:text-indigo-400">
                {{ auth()->user()->role }}
            </span>
        </div>
    </div>

</div>
