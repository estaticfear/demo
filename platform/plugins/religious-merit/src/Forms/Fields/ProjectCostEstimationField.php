<?php

namespace Cmat\ReligiousMerit\Forms\Fields;

use Kris\LaravelFormBuilder\Fields\SelectType;

class ProjectCostEstimationField extends SelectType
{
    protected function getTemplate(): string
    {
        return 'plugins/religious-merit::forms.partials.project-cost-estimation';
    }
}
