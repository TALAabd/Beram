<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoryItemRequest extends FormRequest
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
        return match ($this->getFunctionName()) {
            'create' => $this->store(),
            'update' => $this->update(),
            default => []
        };
    }

    public function store()
    {
        return [
            'story_id' => ['required','numeric','exists:stories,id'],
            'story_type' => ['required','in:video,image','string'],
            'description' => ['string','max:50'],
            'image' => ['required','file'],
            'video' => ['file'],
        ];
    }

    public function update()
    {
        return [
            'story_id' => ['numeric','exists:stories,id'],
            'story_type' => ['in:video,image','string'],
            'description' => ['string','max:50']
        ];
    }

    public function getFunctionName() :string
    {
        $action = $this->route()->getAction();
        $controllerAction = $action['controller'];
        list($controller, $method) = explode('@', $controllerAction);
        return $method;
    }

}
