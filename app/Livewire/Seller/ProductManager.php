<?php

namespace App\Livewire\Seller;

use App\Models\Products;
use App\Models\Store;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProductManager extends Component
{
    public Store $store;
    public $products;

    public $productName = '';
    public $productDescription = '';
    public $productPrice = '';
    public $productStock = '';

    public function mount($storeId)
    {
        // Make sure store belongs to current user
        $this->store = Store::where('id', $storeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = $this->store->products()->latest()->get();
    }

    public function createProduct()
    {
        $this->validate([
            'productName' => 'required|string|max:255',
            'productDescription' => 'nullable|string|max:1000',
            'productPrice' => 'required|numeric|min:0',
            'productStock' => 'required|integer|min:0',
        ]);

        $this->store->products()->create([
            'name' => $this->productName,
            'description' => $this->productDescription,
            'price' => $this->productPrice,
            'stock' => $this->productStock,
        ]);

        // Reset form fields
        $this->reset(['productName', 'productDescription', 'productPrice', 'productStock']);

        // Refresh list
        $this->loadProducts();

        // Optional: flash message or emit event
        session()->flash('success', 'Product created successfully.');
    }


    // Editing Products
    public function loadProduct($id)
    {

        $product = Products::findOrFail($id);

        $this->productName = $product->name;
        $this->productDescription = $product->description;
        $this->productPrice = $product->price;
        $this->productStock = $product->stock;

        $this->dispatch('open-edit-product-modal');
    }

    public function updateProduct($id) {

        $this->validate([
            'productName' => 'required|string|max:255',
            'productDescription' => 'nullable|string|max:1000',
            'productPrice' => 'required|numeric|min:0',
            'productStock' => 'required|integer|min:0',
        ]);

        $product = Products::findOrFail($id);
        $product->name = $this->productName;
        $product->description = $this->productDescription;
        $product->price = $this->productPrice;
        $product->stock = $this->productStock;
        $product->save();

        $this->reset(['productName', 'productDescription', 'productPrice', 'productStock']);
        $this->loadProducts();

    }



    public function render()
    {
        return view('livewire.seller.product-manager');
    }
}
