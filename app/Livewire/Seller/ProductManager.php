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

    public $productId = null;
    public $productName = '';
    public $productDescription = '';
    public $productPrice = '';
    public $productStock = '';
    public $confirmationName = '';

    public function mount($storeId)
    {
        $this->store = Store::where('id', $storeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = $this->store->products()->latest()->get();
    }

    public function openAddProductModal()
    {
        $this->reset(['productName', 'productDescription', 'productPrice', 'productStock', 'productId']);
        $this->dispatch('open-product-modal');
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

        $this->reset(['productName', 'productDescription', 'productPrice', 'productStock']);
        $this->loadProducts();
        session()->flash('success', 'Product created successfully.');
    }

    public function loadProduct_delete($id)
    {
        $product = Products::findOrFail($id);
        $this->productId = $id;
        $this->productName = $product->name;
        $this->confirmationName = '';
        $this->dispatch('open-delete-product-modal');
    }

    public function deleteProduct()
    {
        if ($this->confirmationName !== $this->productName) {
            $this->addError('confirmationName', 'Confirmation name does not match product name.');
            return;
        }

        $product = Products::findOrFail($this->productId);
        $this->dispatch('close-delete-product-modal');
        $product->delete();
        $this->reset(['productId', 'productName', 'confirmationName']);
        $this->loadProducts();
    }

    public function loadProduct($id)
    {
        $product = Products::findOrFail($id);
        $this->productId = $id;
        $this->productName = $product->name;
        $this->productDescription = $product->description;
        $this->productPrice = $product->price;
        $this->productStock = $product->stock;
        $this->dispatch('open-edit-product-modal');
    }

    public function updateProduct()
    {
        $this->validate([
            'productName' => 'required|string|max:255',
            'productDescription' => 'nullable|string|max:1000',
            'productPrice' => 'required|numeric|min:0',
            'productStock' => 'required|integer|min:0',
        ]);

        $product = Products::findOrFail($this->productId);
        $product->update([
            'name' => $this->productName,
            'description' => $this->productDescription,
            'price' => $this->productPrice,
            'stock' => $this->productStock,
        ]);

        $this->reset(['productId', 'productName', 'productDescription', 'productPrice', 'productStock']);
        $this->loadProducts();
    }

    public function render()
    {
        return view('livewire.seller.product-manager');
    }
}






