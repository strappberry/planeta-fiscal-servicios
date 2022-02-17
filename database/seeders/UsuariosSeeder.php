<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Strappberry',
            'email' => 'contacto@strappberry.com',
            'password' => bcrypt('strappberry'),
            'email_verified_at' => now(),
        ]);
    }
}
