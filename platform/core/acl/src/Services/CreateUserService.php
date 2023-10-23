<?php

namespace Cmat\ACL\Services;

use Cmat\ACL\Events\RoleAssignmentEvent;
use Cmat\ACL\Models\User;
use Cmat\ACL\Repositories\Interfaces\RoleInterface;
use Cmat\ACL\Repositories\Interfaces\UserInterface;
use Cmat\Support\Services\ProduceServiceInterface;
use Hash;
use Illuminate\Http\Request;

class CreateUserService implements ProduceServiceInterface
{
    protected UserInterface $userRepository;

    protected RoleInterface $roleRepository;

    protected ActivateUserService $activateUserService;

    public function __construct(
        UserInterface $userRepository,
        RoleInterface $roleRepository,
        ActivateUserService $activateUserService
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
        $this->activateUserService = $activateUserService;
    }

    public function execute(Request $request): User
    {
        /**
         * @var User $user
         */
        $user = $this->userRepository->createOrUpdate($request->input());

        if ($request->has('username') && $request->has('password')) {
            $this->userRepository->update(['id' => $user->id], [
                'username' => $request->input('username'),
                'password' => Hash::make($request->input('password')),
            ]);

            if ($this->activateUserService->activate($user) && $request->input('role_id')) {
                $role = $this->roleRepository->findById($request->input('role_id'));

                if (! empty($role)) {
                    $role->users()->attach($user->id);

                    event(new RoleAssignmentEvent($role, $user));
                }
            }
        }

        return $user;
    }
}
