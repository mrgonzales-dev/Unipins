<x-guest-layout>
    <div class="flex justify-center items-center h-screen">
        <div class="text-center">
            <h1 class="text-3xl font-bold mb-8">Register As</h1>
            <div class="space-x-4">
                <a href="{{ route('register.buyer') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Buyer
                </a>
                <a href="{{ route('register.seller') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Seller
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
