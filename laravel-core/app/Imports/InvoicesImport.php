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
    private $invoiceId;

    public function __construct($invoiceId)
    {
        $this->invoiceId = $invoiceId;
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
        $existingInvoice = InvoicerOrders::where('tracking', $row[0])->first();
        if(!$existingInvoice)
        {
            $order = new InvoicerOrders([
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
                'invoice' => $this->invoiceId,
                'reference' => $row[1],
                'products' => $row[6]
            ]);
            $order->save();
            $this->importedRows[] = $order;
            $order->desk_extra = config('settings.delivery_fees.'.$order->Commune()->Wilaya()->id)-(int)$row[18]; 
            $order->save();
            if(strpos($row[1], "+") !== false)
            {
                $products = explode('+', $row[1]);
            }
            elseif(strpos($row[1], ".") !== false)
            {
                $products = explode('.', $row[1]);
            }
            elseif(strpos($row[1], " و ") !== false)
            {
                $products = explode(' و ', $row[1]);
            }
            else
            {
                $products= [$row[1]];
            }
            $charactersToRemove = array("-", "/", "\\");
            $delivery_extra = ((int)$row[11]-(int)$row[18]);
            foreach($products as $product)
            {
                $product = trim(str_replace($charactersToRemove, "", $product));
                $productQuantity = 1;
                $productRow = null;
                foreach(config('settings.quantities') as $quantity=>$label)
                {
                    if($label != null)
                    {
                        if(strpos($product, $label) === 0)
                        {
                            $productQuantity = $quantity;
                            $productSlug = trim(explode($label, $product)[1]);
                            $productRow = InvoicerProducts::where('slug', 'like', '%'.$productSlug.'%')->where('deleted_at', null)->first();
                            if(!$productRow)
                            {
                                $productRow = InvoicerProducts::create([
                                    'name' => $productSlug,
                                    'slug' => $productSlug,
                                    'confirmed' => false,
                                ]);
                            }
                            break;
                        }
                    }
                }
                if(!$productRow)
                {
                    $productSlug = $product;
                    $productRow = InvoicerProducts::where('slug', 'like', '%'.$productSlug.'%')->where('deleted_at', null)->first();
                    if(!$productRow)
                    {
                        $productRow = InvoicerProducts::create([
                            'name' => $productSlug,
                            'slug' => $productSlug,
                            'confirmed' => false,
                        ]);
                    }
                }
                $delivery_extra -= $productQuantity*$productRow->min_price;
                InvoicerOrdersProducts::create([
                    'order' =>  $order->id,
                    'product' => $productRow->id,
                    'quantity' => $productQuantity,
                ]);
            }
            $order->delivery_extra = $delivery_extra;
            $order->save();
            return $order;
        }
        else
        {
            return redirect()->route('invoicer_invoice', $existingInvoice->invoice);
        }
    }
    

    public function getRows()
    {
        return $this->importedRows;
    }
}
