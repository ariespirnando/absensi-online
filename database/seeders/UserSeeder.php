<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user = [
            [
                'username'=>'admin',
                'name'=>'AkunAdmin',
                'email'=>'admin@gmail.com',
                'level'=>'ADMIN',
                'group_level'=>'ADMIN',
                'password'=>Hash::make('123456')
            ],

            [
                'username'=>'adminbk',
                'name'=>'AkunBK',
                'email'=>'adminbk@gmail.com',
                'level'=>'BK',
                'group_level'=>'ADMIN',
                'password'=>Hash::make('123456')
            ],

            [
                'username'=>'admingutu',
                'name'=>'AkunGuru',
                'email'=>'AkunGuru@gmail.com',
                'level'=>'GURU',
                'group_level'=>'ADMIN',
                'password'=>Hash::make('123456')
            ],

            [
                'username'=>'siswa1',
                'name'=>'AkunSiswa1',
                'email'=>'AkunSiswa1@gmail.com',
                'level'=>'SISWA',
                'group_level'=>'SISWA',
                'password'=>Hash::make('123456')
            ],

            [
                'username'=>'siswa2',
                'name'=>'AkunSiswa2',
                'email'=>'AkunSiswa2@gmail.com',
                'level'=>'SISWA',
                'group_level'=>'SISWA',
                'password'=>Hash::make('123456')
            ],

        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
