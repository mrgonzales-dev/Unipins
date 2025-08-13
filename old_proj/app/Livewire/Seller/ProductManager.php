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
    //DELETE OPTION
    public function removeOptionValue($optionIndex, $valueIndex) {
        $values = $this->options[$optionIndex]['values'];

        // Normalize to array if it's a Collection
        if ($values instanceof \Illuminate\Support\Collection) {
            $values = $values->toArray();
        }

        unset($values[$valueIndex]);

        // Reindex array after unset
        $this->options[$optionIndex]['values'] = array_values($values);
    }
    //==================================

    //====== Product Deletion MODAL ======
    public $confirmationName = '';
    //====================================


    // ======= Product Fetcher =========
    public function fetchProduct($id)
    {
        return Product::with([ 'options', 'options.values'])->findOrFail($id);
    }

    // ================================

    // ======= Product Loader =========
    public function loadProducts()
    {
        $this->products =  Cache::remember("products_store_{$this->store->id}", 10, function () {
            return $this->store->products()->with([
                'media',
                'options.values',
            ])->get();
        });
    }
    //==================================


    public function mount($storeId)
    {
        $this->store = Store::where('id', $storeId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $this->loadProducts();
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
        $product = $this->fetchProduct($id);
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

        $product = $this->fetchProduct($this->productId);
        $this->dispatch('close-delete-product-modal');
        $product->delete();
        $this->reset(['productId', 'productName', 'confirmationName']);
        $this->loadProducts();
    }



    //  ===== Edit Modal ======
    public function editProduct($id)
    {
        $product = $this->fetchProduct($id);

        $this->productId = $id;
        $this->productName = $product->name;
        $this->productDescription = $product->description;
        $this->productPrice = $product->price;
        $this->productStock = $product->stock;
        $this->current_image = $product->media;

        $this->options = $product->options->map(function ($option) {
            return [
                'name' => $option->name,
                'type' => $option->type,
                'values' => $option->values->map(function ($value) {
                    return [
                        'value' => $value->value,
                        'price_adjustment' => $value->price_adjustment,
                    ];
                }),
            ];
        })->toArray();


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

            // Validate option structure

            'options.*.name'        => 'required|string|max:255',
            'options.*.type'        => 'required|string|in:select,radio',
            'options.*.values.*.value' => 'required|string|max:255',
            'options.*.values.*.price_adjustment' => 'nullable|numeric|min:0',

        ]);

        $product = Product::findOrFail($this->productId);

        // Attach product images
        foreach ($this->newImage as $image) {
            $product->addMedia($image)->toMediaCollection('product_images');
        }

        // update the product
        $product->update([
            'name' => $this->productName,
            'description' => $this->productDescription,
            'price' => $this->productPrice,
            'stock' => $this->productStock,
        ]);

        // update existing options and values for the product
        $product->options()->delete();

        foreach ($this->options as $option) {
            $productOption = $product->options()->create([
                'name' => $option['name'],
                'type' => $option['type'], // 'select' or 'radio'
            ]);

            foreach ($option['values'] as $value) {
                $productOption->values()->create([
                    'value' => $value['value'],
                    'price_adjustment' => $value['price_adjustment'],
                ]);
            }
        }


        $this->reset(['productId', 'productName', 'productDescription', 'productPrice', 'productStock', 'options']);
        $this->loadProducts();
        session()->flash('success', 'Product updated successfully.');

    }

    // =============================================================


    public function loadViewProduct($id) {

        $product = $this->fetchProduct($id);
        $this->productName = $product->name;
        $this->productDescription = $product->description;
        $this->productPrice = $product->price;
        $this->productStock = $product->stock;
        $this->current_image = $product->media;

        $this->options = $product->options->map(function ($option) {
            return [
                'name' => $option->name,
                'type' => $option->type,
                'values' => $option->values->map(function ($value) {
                    return [
                        'value' => $value->value,
                        'price_adjustment' => $value->price_adjustment,
                    ];
                }),
            ];
        })->toArray();

        $this->dispatch('open-view-product-modal');

    }


    // ====== import and export functions ======


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
