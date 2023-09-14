<?php

namespace Modules\Resturant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'number' => ['required', 'numeric'],
            'size' => ['required', 'numeric'],
        ];

        switch ($this->method()) {
            case 'POST': {
                    $rules += [
                        'resturant_id' => ['required', 'numeric','exists:resturants,id'],
                        'image' => ['file','required'],
                    ];
                    return $rules;
                }
            case 'PUT': {
                    return $rules;
                }
            case 'DELETE': {
                    return  $rules = [
                        'table_id' => ['required', 'numeric','exists:tables,id'],
                    ];
                }
            default:
                break;
        }
    }
}
