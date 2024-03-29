<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AdminsData;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

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
