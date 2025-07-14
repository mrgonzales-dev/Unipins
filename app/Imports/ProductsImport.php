<?php

namespace App\Imports;

use App\Models\Products;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{

    protected $storeId;

    public function __construct($storeId)
    {
        $this->storeId = $storeId;
    }

    public function model(array $row)
    {
        return new Products([
            'store_id' => $this->storeId,
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'stock' => $row['stock'],
        ]);
    }
}
