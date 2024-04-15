<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use App\Models\Invoicer;
class InvoicesImport implements ToModel, WithStartRow
{
    /**
     * Specify the start row for the import.
     *
     * @return int
     */
    public function startRow(): int
    {
        return 2; // Skip the first row (headers)
    }

    /**
     * Transform the row from the Excel sheet into a model.
     *
     * @param  array  $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        print_r($row);
        // Assuming the structure of the row matches the attributes of your Invoicer model
        /*
        return new Invoicer([
            'total_amount' => sum([])
        ]);
        return new Invoicer([
            'Tracking' => $row[0],
            'Réference' => $row[1],
            'déstinataire' => $row[2],
            'Téléphone' => $row[3],
            'Commune' => $row[4],
            'Wilaya' => $row[5],
            'Produits' => $row[6],
            'Remarque' => $row[7],
            'Poids' => $row[8],
            'Livré le' => $row[9],
            'Encaissé le' => $row[10],
            'montant' => $row[11],
            'Frais de livraison' => $row[12],
            'Frais poids' => $row[13],
            'Frais en extra' => $row[14],
            'Frais SMS' => $row[15],
            'Frais Stockage' => $row[16],
            'Commission recouvrement' => $row[17],
            'Total frais de service' => $row[18],
            'Net recouvert' => $row[19],
            'Type' => $row[20],
            'Type de préstation' => $row[21],
            'Crée le' => $row[22],
            'undefined' => $row[23],
            'Reçu le' => $row[24],
        ]);*/
    }
}
