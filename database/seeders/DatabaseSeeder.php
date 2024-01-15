<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Roles
        $admin = Role::create(['name' => 'admin']);
        $perekammedis = Role::create(['name' => 'perekammedis']);
        $poliUmum = Role::create(['name' => 'poli-umum']);
        $poliNeurologi = Role::create(['name' => 'poli-neurologi']);
        $poliObgyn = Role::create(['name' => 'poli-obgyn']);
        $poliGigi = Role::create(['name' => 'poli-gigi']);
        $poliKulit = Role::create(['name' => 'poli-kulit']);
        $poliOrtopedi = Role::create(['name' => 'poli-ortopedi']);
        $poliPenyakitDalam = Role::create(['name' => 'poli-penyakit-dalam']);
        $poliBedah = Role::create(['name' => 'poli-bedah']);
        $poliAnak = Role::create(['name' => 'poli-anak']);

        // Permissions
        $aksesPoliUmum = Permission::create(['name' => 'akses poli umum']);
        $aksesPoliNeurologi = Permission::create(['name' => 'akses poli neurologi']);
        $aksesPoliObgyn = Permission::create(['name' => 'akses poli obgyn']);
        $aksesPoliGigi = Permission::create(['name' => 'akses poli gigi']);
        $aksesPoliKulit = Permission::create(['name' => 'akses poli kulit']);
        $aksesPoliOrtopedi = Permission::create(['name' => 'akses poli ortopedi']);
        $aksesPoliPenyakitDalam = Permission::create(['name' => 'akses poli penyakit dalam']);
        $aksesPoliBedah = Permission::create(['name' => 'akses poli bedah']);
        $aksesPoliAnak = Permission::create(['name' => 'akses poli anak']);
        $aksesUserManagement = Permission::create(['name' => 'akses user management']);

        // Assign permissions to roles
        $admin->givePermissionTo($aksesPoliUmum, $aksesPoliNeurologi, $aksesPoliObgyn, $aksesPoliGigi, $aksesPoliKulit, $aksesPoliOrtopedi, $aksesPoliPenyakitDalam, $aksesPoliBedah, $aksesPoliAnak, $aksesUserManagement);
        $perekammedis->givePermissionTo($aksesPoliUmum, $aksesPoliNeurologi, $aksesPoliObgyn, $aksesPoliGigi, $aksesPoliKulit, $aksesPoliOrtopedi, $aksesPoliPenyakitDalam, $aksesPoliBedah, $aksesPoliAnak);
        $poliUmum->givePermissionTo($aksesPoliUmum);
        $poliNeurologi->givePermissionTo($aksesPoliNeurologi);
        $poliObgyn->givePermissionTo($aksesPoliObgyn);
        $poliGigi->givePermissionTo($aksesPoliGigi);
        $poliKulit->givePermissionTo($aksesPoliKulit);
        $poliOrtopedi->givePermissionTo($aksesPoliOrtopedi);
        $poliPenyakitDalam->givePermissionTo($aksesPoliPenyakitDalam);
        $poliBedah->givePermissionTo($aksesPoliBedah);
        $poliAnak->givePermissionTo($aksesPoliAnak);

        // Create admin
        $admin = \App\Models\User::factory()->create([
            'email' => 'admin@admin.com',
            'password' => 'admin123'
        ]);

        $admin->assignRole('admin');

        $this->call([
            UserSeeder::class,
            CodeSystemSeeder::class,
            ValueSetSeeder::class,
        ]);
    }
}
