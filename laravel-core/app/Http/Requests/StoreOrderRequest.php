<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->Has_permission('orders_create');
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
            'products.id.*' => 'exists:products,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|regex:/^0[5-7][0-9]{8}$/',
            'phone2' => 'nullable|string|regex:/^0[5-7][0-9]{8}$/',
            'commune' => 'required|exists:communes,id',
            'address' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'total_price' => 'required|integer',
            'delivery_price' => 'required|integer',
            'clean_price' => 'required|integer',
            'conversation' => 'required|exists:facebook_conversations,facebook_conversation_id',
            'intern_tracking' => 'required|string|max:255',
        ];
    }
}
