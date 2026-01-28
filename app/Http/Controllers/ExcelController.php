<?php

namespace App\Http\Controllers;

use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController
{
    public function download()
    {
        return Excel::download(new ProductsExport(), 'product.xlsx');
    }
}
