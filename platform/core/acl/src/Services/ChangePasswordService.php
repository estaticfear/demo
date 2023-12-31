<?php

namespace Cmat\ACL\Services;

use Cmat\ACL\Models\User;
use Illuminate\Support\Facades\Auth;
use Cmat\ACL\Repositories\Interfaces\UserInterface;
use Cmat\Support\Services\ProduceServiceInterface;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Throwable;

class ChangePasswordService implements ProduceServiceInterface
{
    protected UserInterface $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function execute(Request $request): Exception|bool|User
    {
        $currentUser = $request->user();

        if (! $currentUser->isSuperUser()) {
            if (! Hash::check($request->input('old_password'), $currentUser->getAuthPassword())) {
                return new Exception(trans('core/acl::users.current_password_not_valid'));
            }
        }

        $user = $this->userRepository->findOrFail($request->input('id', $currentUser->getKey()));

        $password = $request->input('password');

        $user->password = Hash::make($password);
        $this->userRepository->createOrUpdate($user);

        if ($user->id != $currentUser->getKey()) {
            try {
                Auth::setUser($user)->logoutOtherDevices($password);
            } catch (Throwable $exception) {
                info($exception->getMessage());
            }
        }

        do_action(USER_ACTION_AFTER_UPDATE_PASSWORD, USER_MODULE_SCREEN_NAME, $request, $user);

        return $user;
    }
}
