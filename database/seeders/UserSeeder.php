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
        
        User::create([
            'users' => 'Superadmin',
            'id_funcionario' => null,
            'password' => bcrypt('12345678')
        ])->assignRole('Superadmin');

    }
}
