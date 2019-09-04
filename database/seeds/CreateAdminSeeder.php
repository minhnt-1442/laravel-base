<?php

use App\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => 'Aa@123456'
        ])->assignRole(Role::ADMIN);
    }
}
