<?php
namespace App\Imports;

use App\Models\Commune;
use App\Models\Invoicer;
use App\Models\InvoicerOrders;
use App\Models\InvoicerProducts;
use App\Models\InvoicerOrdersProducts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OrdersImport implements ToModel, WithStartRow
{
    private $importedRows = [];
    private $order;

    public function __construct($invoiceId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Specify the start row for the import.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 3; // Skip the first row (headers)
    }

    /**
     * Transform the row from the Excel sheet into a model.
     *
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        
    }
    

    public function getRows()
    {
        return $this->importedRows;
    }
}
