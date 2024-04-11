<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRemarketingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->Has_Permission("remarketing_edit");
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
            "send_after" => "required|integer",
            "time_unit" => "required|integer|in:1,60,3600,86400",
            "last_message_from" => "required|string|in:user,page,any",
            "make_order" => "required|boolean",
            "since" => "required|string|in:started_at,ended_at,last_from_page_at,last_from_user_at",
            "expire_time_unit" => "required|integer|in:1,60,3600,86400",
            "expire_after" => "nullable|integer",
            'template' => 'required|exists:messages_templates,id',
            #'start_time' => 'nullable|regex:/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/',
            #'end_time' => 'nullable|regex:/^(?:[01][0-9]|2[0-3]):[0-5][0-9]$/',
            "category" => "nullable|exists:remarketing_categories,id",
        ];
    }
}
