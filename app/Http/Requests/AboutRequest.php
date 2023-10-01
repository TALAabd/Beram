<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AboutRequest extends FormRequest
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
        if (request()->routeIs('update')) {
            return $this->update();
        } elseif (request()->routeIs('update-privacy')) {
            return $this->updatePrivacy();
        }elseif (request()->routeIs('update-terms')) {
            return $this->updateTerms();
        }
        return match ($this->getFunctionName()) {
            'update'        => $this->update(),
            'updatePrivacy' => $this->updatePrivacy(),
            'updateTerms'   => $this->updateTerms(),
            default => []
        };
    }
    public function update()
    {
      return [
            'id'       => 'requered',
            'title'    => 'required',
            'content'  => 'required',
            'lang'     => 'required',
            'image'    => 'image',
        ];
    }
    public function updatePrivacy()
    {
      return [
            'lang'     => 'required',
            'privacy'  => 'required',
        ];
    }
    public function updateTerms()
    {
      return [
            'lang'     => 'required',
            'terms'    => 'required',
        ];
    }
}
