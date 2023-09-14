<?php

namespace Modules\Resturant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
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
                        'menu_id' => ['required', 'numeric','exists:menus,id'],
                    ];
                }
            default:
                break;
        }
    }
}
