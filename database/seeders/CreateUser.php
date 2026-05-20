<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            DB::table('users')->insert([
            [
                'name' => 'Admin Utama',
                'username' => 'admin',
                'email' => 'admin@masjid.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ketua Panitia',
                'username' => 'ketua',
                'email' => 'ketua@masjid.com',
                'email_verified_at' => now(),
                'password' => Hash::make('ketua123'),
                'role' => 'ketua',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Wakil Ketua',
                'username' => 'wakil',
                'email' => 'wakil@masjid.com',
                'email_verified_at' => now(),
                'password' => Hash::make('wakil123'),
                'role' => 'wakil',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Bendahara',
                'username' => 'bendahara',
                'email' => 'bendahara@masjid.com',
                'email_verified_at' => now(),
                'password' => Hash::make('bendahara123'),
                'role' => 'bendahara',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sekretaris',
                'username' => 'sekretaris',
                'email' => 'sekretaris@masjid.com',
                'email_verified_at' => now(),
                'password' => Hash::make('sekretaris123'),
                'role' => 'sekretaris',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cak Man',
                'username' => 'cakman',
                'email' => 'cakman@masjid.com',
                'email_verified_at' => now(),
                'password' => Hash::make('cakman123'),
                'role' => 'panitia_qurban',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sholeh',
                'username' => 'sholeh',
                'email' => 'sholeh@masjid.com',
                'email_verified_at' => now(),
                'password' => Hash::make('sholeh123'),
                'role' => 'panitia_qurban',
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
