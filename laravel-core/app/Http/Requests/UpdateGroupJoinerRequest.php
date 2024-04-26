<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGroupJoinerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->Has_permission('group_joiner_edit');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'keywords' => 'required',
            'category' => 'required|array|min:1',
            'category.*' => 'exists:facebook_categories,id',
            'join' => 'required|integer',
            'each' => 'required|integer',
            "time_unit" => "required|integer|in:1,60,3600,86400",
            'max_join' => 'nullable|integer',
        ];
    }
}
