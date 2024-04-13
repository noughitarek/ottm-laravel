<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreRemarketingIntervalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->Has_Permission("remarketing_interval_create");
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required",
            "pages" => "array",
            "pages.*" => "exists:facebook_pages,facebook_page_id",
            "start_after" => "required|integer",
            "start_time_unit" => "required|integer|in:1,60,3600,86400",
            "send_after_each" => "required|integer",
            "time_unit" => "required|integer|in:1,60,3600,86400",
            "devide_by" => "required|integer",
            'template' => 'required|array|min:1',
            'template.*' => 'nullable|exists:messages_templates,id',
            "category" => "nullable|exists:remarketing_categories,id",
        ];
    }
}
