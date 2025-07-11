<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Store;



class StoreManager extends Component
{
    //owned stores
    public $ownedStores;

    //description
    public $name = '';
    public $description = '';

    //deletion process
    public $storeId;
    public $storeName;
    public $confirmationStoreName = '';



    public function mount()
    {
        // Load stores belonging to the authenticated seller
        $this->ownedStores = Auth::user()->ownedStores()->get();
    }

    public function createStore() {

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        Auth::user()->ownedStores()->create([
                    'user_id' => Auth::user()->id,
                    'name' => $this->name,
                   'description' => $this->description
        ]);

        $this->reset('name', 'description');
        $this->dispatch('close-store-modal');
        //load stores
        $this->ownedStores = Auth::user()->ownedStores()->get();
        session()->flash('success', 'Store created successfully.');
    }

    public function loadStore_delete($id) {
        $store = Store::findOrFail($id);
        $this->storeId = $id;
        $this->storeName = $store->name;
        $this->confirmationStoreName = '';

        $this->dispatch('open-delete-store-modal');
    }

    public function deleteStore() {

        if ($this->confirmationStoreName !== $this->storeName) {
            $this->addError('confirmationStoreName', 'Confirmation name does not match store name.');
            return;
        }

        $store = Store::findOrFail($this->storeId);
        $this->dispatch('close-delete-store-modal');
        $store->delete();
        $this->reset(['storeId', 'storeName', 'confirmationStoreName']);
        $this->ownedStores = Auth::user()->ownedStores()->get();

    }


    public function render()
    {
        return view('livewire.seller.store-manager', [
            'ownedStores' => $this->ownedStores
        ]);
    }
}
