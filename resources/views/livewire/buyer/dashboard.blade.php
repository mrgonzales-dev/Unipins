<!-- Seller Dashboard with buyer-specific tools and features -->
<!-- this uses tailwind -->
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Buyer Dashboard</h1>
    <p>Welcome, {{ auth()->user()->name }}!</p>

    <ul class="mt-4 list-disc list-inside text-gray-700">
        <li><a href="#">Browse Products</a></li>
        <li><a href="#">Add to Cart</a></li>
        <li><a href="#">Checkout</a></li>
    </ul>
</div>
