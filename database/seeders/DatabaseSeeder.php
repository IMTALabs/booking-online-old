<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\booking;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        // \App\Models\booking::query()->create([
        //     'schedule_id' => '9',
        //     'user_id' => '7',
        //     'status'=>'đang chờ xác nhận',
        //     'booking_time' => '2024-05-21 09:00:00'
        // ]);
        // \App\Models\Service::query()->create([
        //     'categorie_id' => '1',
        //     'name' => '14',
        //     'price'=>'123',
        //     'describe' => 'aaaaaaaaaaa'
        // ]);
        // \App\Models\StoreInformation::query()->create([
        //     'name' => 'StoreTesst',
        //     'image' => 'asdasdasda.jpg',
        //     'address' => 'Thanh Ha - Hai Duong',
        //     'phone' => '0926755061',
        // ]);
        DB::table('users')->insert([
            [

                'email' => 'admin1@gmail.com',
                'role' => 0,
                'name' => 'Admin1',
                'password' => Hash::make('12345678'),

            ],
            [
                'email' => 'admin2@gmail.com',
                'role' => 0,
                'name' => 'Admin2',
                'password' => Hash::make('12345678'),
            ],
            [
                'email' => 'admin3@gmail.com',
                'role' => 0,
                'name' => 'Admin3',
                'password' => Hash::make('12345678'),
            ],
            [
                'email' => 'admin5@gmail.com',
                'role' => 0,
                'name' => 'Admin4',
                'password' => Hash::make('12345678'),
            ],
        ],
        );
    }
}
