<?php

namespace Cmat\Ecommerce\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\FormField;

class CategoryMultiField extends FormField
{
    protected function getTemplate(): string
    {
        return 'plugins/ecommerce::product-categories.partials.categories-multi';
    }
}
