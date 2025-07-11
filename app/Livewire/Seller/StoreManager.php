<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class StoreManager extends Component
{
    public $ownedStores;

    public $name = '';
    public $description = '';


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

    public function render()
    {
        return view('livewire.seller.store-manager', [
            'ownedStores' => $this->ownedStores
        ]);
    }
}
