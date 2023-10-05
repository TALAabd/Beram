<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'banner_type' => 'required|in:section_1,section_2,section_3,website',
            'title' => 'nullable|string',
            'url_link' => 'nullable|url',
            'service' => 'nullable|in:hotel,restaurant',
            'provider_id' => 'nullable|integer|exists:users,id',
            'images.*.name' => 'nullable|file',
            'description' => 'nullable|string',
        ];
        return $rules;
    }
}
