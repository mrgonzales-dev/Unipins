<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Seller Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}!</p>

    {{-- Add seller-specific tools --}}
    <ul class="mt-4 list-disc list-inside text-gray-700">
        <li><a href="#">Manage Products</a></li>
        <li><a href="#">View Sales</a></li>
        <li><a href="#">Update Store Info</a></li>
    </ul>
</div>
