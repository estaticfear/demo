<?php

namespace Database\Seeders;

use Cmat\ACL\Models\User;
use Cmat\ACL\Repositories\Interfaces\ActivationInterface;
use Cmat\Base\Supports\BaseSeeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends BaseSeeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();

        $user = new User();
        $user->first_name = 'System';
        $user->last_name = 'Admin';
        $user->email = 'admin@cmat.com';
        $user->username = 'cmat';
        $user->password = bcrypt('cmatwinwin');
        $user->super_user = 1;
        $user->manage_supers = 1;
        $user->save();

        $activationRepository = app(ActivationInterface::class);

        $activation = $activationRepository->createUser($user);

        $activationRepository->complete($user, $activation->code);
    }
}
