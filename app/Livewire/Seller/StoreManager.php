<?php

namespace App\Livewire\Seller;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class StoreManager extends Component
{
    public function render()
    {
        return view('livewire.seller.store-manager');
    }
}
