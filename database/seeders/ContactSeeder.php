<?php

namespace Database\Seeders;

use Cmat\Base\Supports\BaseSeeder;
use Cmat\Contact\Enums\ContactStatusEnum;
use Cmat\Contact\Models\Contact;
use Faker\Factory;

class ContactSeeder extends BaseSeeder
{
    public function run(): void
    {
        $faker = Factory::create();

        Contact::truncate();

        for ($i = 0; $i < 10; $i++) {
            Contact::create([
                'name' => $faker->name(),
                'email' => $faker->safeEmail(),
                'phone' => $faker->phoneNumber(),
                'address' => $faker->address(),
                'subject' => $faker->text(50),
                'content' => $faker->text(500),
                'status' => $faker->randomElement([ContactStatusEnum::READ, ContactStatusEnum::UNREAD]),
            ]);
        }
    }
}
