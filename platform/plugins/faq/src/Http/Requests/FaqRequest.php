<?php

namespace Cmat\Faq\Http\Requests;

use Cmat\Support\Http\Requests\Request;

class FaqRequest extends Request
{
    public function rules(): array
    {
        return [
            'category_id' => 'required',
            'question' => 'required',
            'answer' => 'required',
        ];
    }
}
