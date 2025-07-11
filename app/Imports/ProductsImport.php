<?php

namespace App\Imports;

use App\Models\Products;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, withHeadingRow
{

    public function model(array $row)
    {
        $ownedStore = User::find(Auth::id())->ownedStores()->first();

        if (!$ownedStore) {
            return null;
        }

        return new Products([
            'store_id' => $ownedStore->id,
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock' => $row['stock'],
        ]);
    }
}
