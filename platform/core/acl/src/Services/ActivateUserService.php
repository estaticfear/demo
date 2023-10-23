<?php

namespace Cmat\ACL\Services;

use Cmat\ACL\Models\User;
use Cmat\ACL\Repositories\Interfaces\ActivationInterface;

class ActivateUserService
{
    protected ActivationInterface $activationRepository;

    public function __construct(ActivationInterface $activationRepository)
    {
        $this->activationRepository = $activationRepository;
    }

    public function activate(User $user): bool
    {
        if ($this->activationRepository->completed($user)) {
            return false;
        }

        event('acl.activating', $user);

        $activation = $this->activationRepository->createUser($user);

        event('acl.activated', [$user, $activation]);

        return $this->activationRepository->complete($user, $activation->code);
    }
}
