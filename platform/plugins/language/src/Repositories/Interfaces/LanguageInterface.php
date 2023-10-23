<?php

namespace Cmat\Language\Repositories\Interfaces;

use Cmat\Support\Repositories\Interfaces\RepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

interface LanguageInterface extends RepositoryInterface
{
    public function getActiveLanguage(array $select = ['*']): Collection;

    public function getDefaultLanguage(array $select = ['*']): ?Model;
}
