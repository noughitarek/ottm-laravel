<?php
namespace App\Imports;

use App\Models\Commune;
use App\Models\Invoicer;
use App\Models\InvoicerOrders;
use App\Models\InvoicerProducts;
use App\Models\InvoicerOrdersProducts;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class InvoicesImport implements ToModel, WithStartRow
{
    private $importedRows = [];

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
        $exisitngInvoice = InvoicerOrders::where('tracking', $row[0])->first();
        /*if($exisitngInvoice)
        {*/
            $invoice = new InvoicerOrders([
                'name' => $row[2],
                'phone' => explode('/', $row[3])[0],
                'phone2' => explode('/', $row[3])[1]??null,
                'address' => 'n/a',
                'commune' => Commune::where('name', $row[4])->first()->id,
                'total_price' => (int)$row[11],
                'delivery_price' => (int)$row[18],
                'clean_price' => ((int)$row[11]-(int)$row[18]),
                'recovered' => (int)$row[19],
                'tracking' => $row[0],
                'stopdesk' => $row[21]=="Stop Desk",
                'facebook_conversation_id' => "n",
            ]);
            $this->importedRows[] = $invoice;
            $products = InvoicerProducts::whereNull('deleted_at')->get();
            $orderProducts = explode('+', $row[6]);
            foreach($products as $product)
            {
                foreach($product->Quantity_Prices() as $index=>$quantity)
                {
                    if(
                        (in_array($quantity['title'].' '.$product->slug, $orderProducts)) ||
                        (in_array($quantity['title'].$product->slug, $orderProducts)) ||
                        (in_array($quantity['title'].' '.trim($product->slug), $orderProducts)) ||
                        (in_array($quantity['title'].trim($product->slug), $orderProducts))
                    )
                    {
                        if($quantity['title'] != null)
                        {
                            InvoicerOrdersProducts::create([
                                'product' => $product->id,
                                'quantity' => $index,
                            ]);
                        }
                    }
                }
                if(
                    (in_array($product->slug, $orderProducts)) ||
                    (in_array(trim($product->slug), $orderProducts))
                )
                {
                    InvoicerOrdersProducts::create([
                        'product' => $product->id,
                        'quantity' => 1,
                    ]);

                }
            }
                /*
            $products = explode('+', $row[6]);
            $realProducts = InvoicerProducts::where('deleted_at', false)->get();
            foreach($products as $product)
            {
                if(strpos(trim(explode('/', $product)[0]), 'زوج') === 0)
                {
                    $quantity = '2';
                }
                else 
                {
                    $quantity = '1';
                }
                $product = isset(explode('/', $product)[1])?explode('/', $product)[1]:explode('/', $product)[0];
                echo '<br>';
            }*/

            return $invoice;
        #}
    }
    

    public function getRows()
    {
        return $this->importedRows;
    }
}
