<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;
use App\Models\User;

class StoreManager extends Component
{

    public $stores = [];
    public $name = '';
    public $description = '';

    public function mount()
    {
        if (!Auth::check()) {
            abort(403);
        }

        $this->stores = Auth::user()->stores;
    }

    public function createStore() {
        $this->validate([
            'name' => 'required',
            'description' => 'required',
        ]);

        Auth::user()->stores()->create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        $this->reset(['name', 'description']);
        $this->stores = Auth::user()->stores()->get();
        session()->flash('success', 'Store created successfully.');
    }

    public function render()
    {
        return view('livewire.seller.store-manager');
    }
}
