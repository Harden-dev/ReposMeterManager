<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        User::create([
            'first_name' => 'Kouame',
            'last_name' => 'Michel',
            'email' => 'michel.banh@softskills.ci',
            'password' => bcrypt('password'),

        ]);

    }
}
