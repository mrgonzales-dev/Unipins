<?php



namespace App\Livewire\Seller;

use App\Models\Product;
use App\Models\Store;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


// Spatie Media Integration
use Livewire\WithFileUploads;
//===========================

//imports and exports
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;
use App\Exports\ProductsExport;
use Illuminate\Support\Facades\Cache;

class ProductManager extends Component
{
    // ===== Main Variables =====
    public Store $store;
    public $products;
    //===========================

    // ===== SPATIE Media Uploads=====
    use WithFileUploads;
    public $productImages = [];
    public $newImage = [];
    //=================================

    //==== Excel Import =====
    public $importFile;
    //=======================

    // ===== Product Modal ======
    public $current_image = [];
    public $productId = null;
    public $productName = '';
    public $productDescription = '';
    public $productPrice = '';
    public $productStock = '';
    // ===========================

    // ==== Product Variant Modal =====
    public $options = [];

    public function addOption() {
        $this->options[] = [
            'name' => '',
            'type' => 'select',
            'values' => []
        ];
    }

    public function addOptionValue($optionIndex) {

        $this->options[$optionIndex]['values'][] = [
            'value' => '',
            'price_adjustment' => 0
        ];

    }

    //==================================

    //====== Product Deletion MODAL ======
    public $confirmationName = '';
    //====================================


    public function mount($storeId)
    {
        $this->store = Store::where('id', $storeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $this->loadProducts();
    }



    public function loadProducts()
    {
        $this->products =  Cache::remember('products', 10, function () {
            return $this->store->products()->with([
                'media',
                'options.values',
            ])->get();
        });
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
            'productImages.*' => 'image|max:2048', // 2MB
            // options
            'options.*.name' => 'required|string|max:255',
            'options.*.type' => 'required|in:select,radio',
            'options.*.values.*.value' => 'required|string|max:255',
            'options.*.values.*.price_adjustment' => 'required|numeric|min:0',
        ]);

        // create product
        $product = $this->store->products()->create([
            'name' => $this->productName,
            'description' => $this->productDescription,
            'price' => $this->productPrice,
            'stock' => $this->productStock,
        ]);

        // create product options
        foreach ($this->options as $option) {
            $productOption = $product->options()->create([
                'name' => $option['name'],
                'type' => $option['type'], // 'select' or 'radio'
            ]);

            foreach ($option['values'] as $value) {
                $productOption->values()->create([
                    'value' => $value['value'],
                    'price_adjustment' => $value['price_adjustment'] ?? 0,
                ]);
            }
        }


        // Attach product images
        foreach ($this->productImages as $image) {
            $product->addMedia($image)->toMediaCollection('product_images');
        }

        $this->reset(['productName', 'productDescription', 'productPrice', 'productStock', 'productImages', 'options']);
        $this->loadProducts();
        session()->flash('success', 'Product created successfully.');
    }

    public function loadProduct_delete($id)
    {
        $product = Product::findOrFail($id);
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

        $product = Product::findOrFail($this->productId);
        $this->dispatch('close-delete-product-modal');
        $product->delete();
        $this->reset(['productId', 'productName', 'confirmationName']);
        $this->loadProducts();
    }


    public function loadViewProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->current_image = $product->getMedia('product_images');
        $this->productId = $id;
        $this->productName = $product->name;
        $this->productDescription = $product->description;
        $this->productPrice = $product->price;
        $this->productStock = $product->stock;
        $this->dispatch('open-view-product-modal');
    }

    public function loadProduct($id)
    {

        $product = Product::findOrFail($id);
        $this->current_image = $product->getMedia('product_images');
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
            'productName'        => 'required|string|max:255',
            'productDescription' => 'nullable|string|max:1000',
            'productPrice'       => 'required|numeric|min:0',
            'productStock'       => 'required|integer|min:0',
            'newImage.*'         => 'nullable|image|max:2048',
        ]);

        $product = Product::findOrFail($this->productId);

        // 1️⃣ Core attributes
        $product->update([
            'name'        => $this->productName,
            'description' => $this->productDescription,
            'price'       => $this->productPrice,
            'stock'       => $this->productStock,
        ]);

        // 2️⃣ Debug: what’s in newImage?
        Log::debug('UPDATE_PRODUCT: newImage property dump', ['newImage' => $this->newImage]);
        session()->flash('debug', '🛠️ Uploaded files: ' . count($this->newImage));

        if (! empty($this->newImage)) {
            // Debug before clearing
            Log::debug('UPDATE_PRODUCT: clearing media collection', ['collection' => 'product_images']);
            $product->clearMediaCollection('product_images');

            foreach ($this->newImage as $idx => $upload) {
                try {
                    $realPath = $upload->getRealPath();
                    Log::debug("UPDATE_PRODUCT: adding #{$idx}", [
                        'originalName' => $upload->getClientOriginalName(),
                        'realPath'     => $realPath,
                    ]);

                    $product
                        ->addMedia($realPath)
                        ->toMediaCollection('product_images');

                } catch (\Exception $e) {
                    // Log any errors during addMedia
                    Log::error("UPDATE_PRODUCT: failed to attach image #{$idx}", [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    session()->flash('error', "Failed to attach image #{$idx}: " . $e->getMessage());
                }
            }
            session()->flash('debug', '🛠️ Attached images: ' . count($product->getMedia('product_images')));
        }

        //  Reset & reload
        $this->dispatch('close-edit-product-modal');
        $this->reset([
            'productId',
            'productName',
            'productDescription',
            'productPrice',
            'productStock',
            'newImage',
        ]);
        $this->loadProducts();

        session()->flash('success', 'Product updated successfully.');
    }

    public function openImportProductModal() {
        $this->dispatch('open-import-product-modal');
    }

    public function importProducts() {

        $this->validate([
            'importFile' => 'required|file|mimes:xlsx,xls',
        ]);

        Excel::import(new ProductsImport($this->store->id), $this->importFile);
        session()->flash('success', 'Products imported successfully.');
        $this->loadProducts();
    }

    public function exportProducts() {
        //export specific products from store
        return Excel::download(new ProductsExport($this->store->id), "{$this->store->name}_products.xlsx");
    }

    public function render()
    {
        return view('livewire.seller.product-manager');
    }
}
