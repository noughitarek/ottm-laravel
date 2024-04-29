<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->Has_Permission("accounting_purchases_create");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'products' => 'required|array|min:1',
            'products.id.*' => 'required|exists:products,id',
            'products.unit_price.*' => 'required|integer|min:0',
            'products.quantity.*' => 'required|integer|min:1',
            'products.desk.*' => 'required|exists:desks,id',
            'total_amount' => 'required|integer|min:0',
            'tests_funding' => 'nullable|exists:fundings,id',
            'products_funding' => 'nullable|exists:fundings,id',
        ];
    }
}
