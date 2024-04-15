<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoicer extends Model
{
    use HasFactory;
    protected $fillable = ['Tracking', 'Réference', 'déstinataire', 'Téléphone', 'Commune', 'Wilaya', 'Produits', 'Remarque', 'Poids', 'Livré le', 'Encaissé le', 'montant', 'Frais de livraison', 'Frais poids', 'Frais en extra', 'Frais SMS', 'Frais Stockage', 'Commission recouvrement', 'Total frais de service', 'Net recouvert', 'Type', 'Type de préstation', 'Crée le', 'undefined', 'Reçu le'];
}
