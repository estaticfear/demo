<?php

namespace Cmat\ACL\Commands;

use Cmat\ACL\Repositories\Interfaces\UserInterface;
use Cmat\ACL\Services\ActivateUserService;
use Cmat\Base\Commands\Traits\ValidateCommandInput;
use Cmat\Base\Supports\Helper;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('cms:user:create', 'Create a super user')]
class UserCreateCommand extends Command
{
    use ValidateCommandInput;

    public function handle(UserInterface $userRepository, ActivateUserService $activateUserService): int
    {
        $this->components->info('Creating a super user...');

        try {
            $user = $userRepository->getModel();
            $user->first_name = $this->askWithValidate('Enter first name', 'required|min:2|max:60');
            $user->last_name = $this->askWithValidate('Enter last name', 'required|min:2|max:60');
            $user->email = $this->askWithValidate('Enter email address', 'required|email|unique:users,email');
            $user->username = $this->askWithValidate('Enter username', 'required|min:4|max:60|unique:users,username');
            $user->password = Hash::make($this->askWithValidate('Enter password', 'required|min:6|max:60', true));
            $user->super_user = 1;
            $user->manage_supers = 1;

            $userRepository->createOrUpdate($user);

            if ($activateUserService->activate($user)) {
                $this->components->info('Super user is created.');
            }

            Helper::clearCache();

            return self::SUCCESS;
        } catch (Exception $exception) {
            $this->components->error('User could not be created.');
            $this->components->error($exception->getMessage());

            return self::FAILURE;
        }
    }
}
