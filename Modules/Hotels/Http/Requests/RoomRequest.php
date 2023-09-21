<?php

namespace Modules\Hotels\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\ApiResponser;

class RoomRequest extends FormRequest
{
    use ApiResponser;
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {

        $rules = [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255'],
            'syrian_price' => ['required', 'numeric'],
            'foreign_price' => ['required', 'numeric'],
            'number' => ['required', 'numeric'],
            'baths' => ['required', 'numeric'],
            'beds' => ['required', 'numeric'],
            'size' => ['required', 'numeric'],
            'adults' => ['required', 'numeric'],
            'children' => ['required', 'numeric'],
        ];

        switch ($this->method()) {
            case 'POST': {
                    $rules += [
                        'hotel_id' => ['required', 'numeric'],
                        'image' => ['file','required'],
                    ];
                    return $rules;
                }
            case 'PUT': {
                    return $rules;
                }
            case 'DELETE': {
                    return  $rules = [
                        'room_id' => ['required', 'numeric'],
                    ];
                }
            default:
                break;
        }
    }

}
