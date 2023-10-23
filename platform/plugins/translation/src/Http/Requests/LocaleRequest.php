<?php

namespace Cmat\Translation\Http\Requests;

use Cmat\Base\Supports\Language;
use Cmat\Support\Http\Requests\Request;

class LocaleRequest extends Request
{
    public function rules(): array
    {
        return [
            'locale' => 'required|in:' . implode(',', collect(Language::getListLanguages())->pluck(0)->unique()->all()),
        ];
    }
}
