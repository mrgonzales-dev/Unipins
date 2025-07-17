<?php

namespace App\Livewire\Buyer;

use Livewire\Component;
use App\Models\Products;
use App\Models\Store;
use Illuminate\Support\Facades\Cache;

class ProductCatalogue extends Component
{

    public ?Store $store = null;


    //Ui states
    public $products = [];
    public $query = ''; //search
    public $storeId = null;
    public $current_image = [];
    public $productName = '';
    public $productDescription = '';
    public $productPrice = '';
    public $productStock = '';


    //load all products
    public function mount() {
        $this->loadProducts();
    }

    public function updateQuery(): void {
        $this->loadProducts();
    }

    public function loadProducts() {
        $this->products =  Cache::remember('products', 10, function () {
            return Products::with(['store','media'])->latest()->get();

        });
    }

    public function loadViewProduct($id): void {
        $product = Products::findOrFail($id);
        $this->current_image        = $product->getMedia('product_images');
        $this->productName          = $product->name;
        $this->productDescription   = $product->description;
        $this->productStock         = $product->stock;
        $this->productPrice         = $product->price;
        $this->dispatch('open-view-product-modal');
    }

    public function loadSingleProductView($id): void {
        $product = Products::findOrFail($id);
        $this->current_image        = $product->getMedia('product_images');
        $this->productName          = $product->name;
        $this->productDescription   = $product->description;
        $this->productStock         = $product->stock;
        $this->productPrice         = $product->price;
        $this->dispatch('open-view-product-modal');
    }


    public function render(){
        return view('livewire.buyer.product-catalogue');
    }
}
