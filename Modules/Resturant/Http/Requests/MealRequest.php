<?php

namespace Modules\Resturant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MealRequest extends FormRequest
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
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
        ];

        switch ($this->method()) {
            case 'POST': {
                    $rules += [
                        'menu_id' => ['required', 'numeric','exists:menus,id'],
                        'image' => ['file','required'],
                    ];
                    return $rules;
                }
            case 'PUT': {
                    return $rules;
                }
            case 'DELETE': {
                    return  $rules = [
                        'mealId' => ['required', 'numeric','exists:meals,id'],
                    ];
                }
            default:
                break;
        }
    }
}
