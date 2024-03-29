<?php

namespace Database\Seeders;

use App\Models\AdminsData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name'  =>  "Admin Tester",
            'email'  =>  "emailTester@gmail.com",
            'no_telp'  =>  "081275478484",
            'level'  =>  "admin",
            'username'  =>  "admin",
            'password'  =>  Hash::make("123456"),
        ];
        AdminsData::create($data);
    }
}
