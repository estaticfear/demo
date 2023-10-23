<?php

namespace Cmat\Faq\Repositories\Caches;

use Cmat\Support\Repositories\Caches\CacheAbstractDecorator;
use Cmat\Faq\Repositories\Interfaces\FaqInterface;

class FaqCacheDecorator extends CacheAbstractDecorator implements FaqInterface
{
    public function getAllActiveFaqs($perPage = 12)
    {
        return $this->getDataIfExistCache(__FUNCTION__, func_get_args());
    }

}
