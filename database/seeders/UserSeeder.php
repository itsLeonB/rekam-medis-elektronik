<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hapus semua user
        User::truncate();

        $leon = User::create([
            'name' => 'Ellion Blessan',
            'email' => 'ellionblessan@gmail.com',
            'password' => '12345678',
            'remember_token' => Str::random(10)
        ]);

        $leon->assignRole('perekammedis');

        $mando = User::create([
            'name' => 'Yushi',
            'email' => 'armandorizqy2326@gmail.com',
            'password' => '12345678',
            'remember_token' => Str::random(10)
        ]);

        $mando->assignRole('perekammedis');

        $putri = User::create([
            'name' => "Putri Isabella",
            'email' => 'putri@gmail.com',
            'pasword' => '12345678',
            'remember_token' => Str::random(10)
        ]);
        $putri->assignRole('poli-umum');

        $daffa = User::create([
            'name' => "daffa daris",
            'email' => 'daffa@gmail.com',
            'password' => '12345678',
            'remember_token' => Str::random(10)
        ]);
        $daffa->assignRole('apoteker');

        $dimas = User::create([
            'name' => "dimas",
            'email' => 'dimas@mail.com',
            'password' => '12345678',
            'remember_token' => Str::random(10)
        ]);
        $dimas->assignRole('keuangan');
    }
}
