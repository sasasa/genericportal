<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if( env('APP_ENV', 'production') === 'local' ) {
            User::create([
                'name' => 'saeki',
                'email' => 'masaakisaeki@gmail.com',
                'password' => bcrypt('hogemoge'),
                'email_verified_at' => now(),
            ]);
            User::create([
                'name' => 'grow-up123',
                'email' => 'grow-up123@gmail.com',
                'password' => bcrypt('grow-up123'),
                'email_verified_at' => now(),
            ]);
        } else {
            User::create([
                'name' => 'grow-up123',
                'email' => 'grow-up123@gmail.com',
                'password' => bcrypt('grow-up123'),
                'email_verified_at' => now(),
            ]);
        }
    }
}
