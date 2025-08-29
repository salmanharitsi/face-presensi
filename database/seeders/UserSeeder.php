<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'name' => 'Restu Sitompul',
                'nip' => '12345678935',
                'email' => 'restu@gmail.com',
                'password' => 'restu123',
                'role' => 'guru',
            ],
            [
                'name' => 'Ririn Rahmadani, S.Pd',
                'nip' => '12345678938',
                'email' => 'ririn@gmail.com',
                'password' => 'ririn123',
                'role' => 'guru',
            ],
            [
                'name' => 'Andri Gultom, S.Pd',
                'nip' => '12345678931',
                'email' => 'andri@gmail.com',
                'password' => 'andri123',
                'role' => 'guru',
            ],
            [
                'name' => 'Diana Wulandari, S.Pd',
                'nip' => '12345678932',
                'email' => 'diana@gmail.com',
                'password' => 'diana123',
                'role' => 'guru',
            ],
            [
                'name' => 'Lestika Triyuni, S.Pd',
                'nip' => '12345678933',
                'email' => 'lestika@gmail.com',
                'password' => 'lestika123',
                'role' => 'guru',
            ],
            [
                'name' => 'Nurhamimah, S.Ag',
                'nip' => '12345678934',
                'email' => 'nurhamimah@gmail.com',
                'password' => 'nurhamimah123',
                'role' => 'guru',
            ],
            [
                'name' => 'Nurmalysa, S.Pd',
                'nip' => '12345678936',
                'email' => 'nurmalysa@gmail.com',
                'password' => 'nurmalysa123',
                'role' => 'guru',
            ],
            [
                'name' => 'Richardo Panjaitan, S.Pd',
                'nip' => '12345678937',
                'email' => 'richardo@gmail.com',
                'password' => 'richardo123',
                'role' => 'guru',
            ],
            [
                'name' => 'Zahra Syafitri, S.Pd',
                'nip' => '123456789392',
                'email' => 'zahra@gmail.com',
                'password' => 'zahra123',
                'role' => 'guru',
            ],
            [
                'name' => 'Siti Nur Alfi, S.Pd',
                'nip' => '123456789399',
                'email' => 'siti@gmail.com',
                'password' => 'siti123',
                'role' => 'kepsek',
            ],
            [
                'name' => 'Administrator',
                'nip' => '12345678943240',
                'email' => 'admin@gmail.com',
                'password' => 'admin123',
                'role' => 'admin',
            ]
        ];
        
        foreach ($user as $value) {
            User::create([
                'name' => $value['name'],
                'nip' => $value['nip'],
                'email' => $value['email'],
                'password' => $value['password'],
                'role' => $value['role'],
            ]);
        }
    }
}
