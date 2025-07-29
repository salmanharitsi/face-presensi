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
                'name' => 'John Doe',
                'nip' => '1234567890',
                'email' => 'guru1@gmail.com',
                'password' => 'guru123',
                'role' => 'guru',
            ],
            [
                'name' => 'Kim',
                'nip' => '1234567891',
                'email' => 'guru2@gmail.com',
                'password' => 'guru123',
                'role' => 'guru',
            ],
            [
                'name' => 'Salman Al Haritsi',
                'nip' => '12345678934',
                'email' => 'salmanalharitsi14@gmail.com',
                'password' => 'salman123',
                'role' => 'guru',
            ],
            [
                'name' => 'Septania Daniati Panjaitan',
                'nip' => '12345678935',
                'email' => 'septania@gmail.com',
                'password' => 'septa123',
                'role' => 'guru',
            ],
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
