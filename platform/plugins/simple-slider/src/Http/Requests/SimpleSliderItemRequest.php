<?php

namespace Cmat\SimpleSlider\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class SimpleSliderItemRequest extends Request
{
    public function rules(): array
    {
        return [
            'simple_slider_id' => 'required',
            'title' => 'max:255',
            'image' => 'required',
            'order' => 'required|integer|min:0|max:1000',
        ];
    }
}
