<?php

namespace App\Exports;

use App\Models\Products;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection, WithHeadings
{

    protected $storeId;


    public function __construct($storeId)
    {
        $this->storeId = $storeId;
    }

    public function collection()
    {
        return Products::where('store_id', $this->storeId)->select('name', 'description', 'price', 'stock')->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Price',
            'Stock',
        ];
    }
}
