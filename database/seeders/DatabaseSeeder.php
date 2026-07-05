<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@sijenggung.desa.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Pegawai Tester',
            'email' => 'pegawai@sijenggung.desa.id',
            'password' => bcrypt('password'),
            'role' => 'pegawai',
            'nip' => '198501012010121001',
            'jabatan' => 'Kaur Keuangan',
        ]);
    }
}
