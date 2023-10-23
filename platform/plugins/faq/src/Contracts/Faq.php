<?php

namespace Cmat\Faq\Contracts;

use Cmat\Faq\FaqCollection;

interface Faq
{
    public function registerSchema(FaqCollection $faqs): void;
}
