<?php

namespace Cmat\ACL\Repositories\Interfaces;

use Cmat\ACL\Models\Activation;
use Cmat\ACL\Models\User;

interface ActivationInterface
{
    public function createUser(User $user): Activation;

    public function exists(User $user, string|null $code = null): Activation|bool;

    public function complete(User $user, string $code): bool;

    public function completed(User $user): Activation|bool;

    public function remove(User $user);

    public function removeExpired();
}
