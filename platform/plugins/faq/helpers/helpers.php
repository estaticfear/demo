<?php

use Cmat\Faq\Repositories\Interfaces\FaqInterface;
use Illuminate\Database\Eloquent\Collection;

if (! function_exists('get_all_active_faqs')) {
    function get_all_active_faqs(int $limit): Collection
    {
        return app(FaqInterface::class)->getAllActiveFaqs($limit);
    }
}
