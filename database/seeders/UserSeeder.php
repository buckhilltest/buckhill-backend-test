<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory([
            'email' => 'admin@buckhill.co.uk',
            'password' => '$2a$12$pt1aGLJBUd.TGZXWSxcd4Og7SmmyG5ECIV9BsQuWeV0botmEqMSSq' // admin
        ])->admin()
            ->create();

        User::factory()->count(10)->create();
    }
}
