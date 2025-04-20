<?php

namespace Database\Seeders;

use App\Modules\User\Model\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $default_password = '88888888';

        $users = [
            'user_1' => [
                'name' => 'Edward',
                'email' => 'edward@gmail.com',
                'full_name' => 'Edward',
                'phone_number' => Str::random(8),
                'company' => 'Company A',
                'address' => 'Test address 1',
                'password' => Hash::make($default_password)
            ],

            'user_2' => [
                'name' => 'Daniel',
                'email' => 'daniel@gmail.com',
                'full_name' => 'Daniel',
                'phone_number' => Str::random(8),
                'company' => 'Company B',
                'address' => 'Test address 2',
                'password' => Hash::make($default_password)
            ],

            'user_3' => [
                'name' => 'Billy',
                'email' => 'billy@gmail.com',
                'full_name' => 'Billy',
                'phone_number' => Str::random(8),
                'company' => 'Company C',
                'address' => 'Test address 3',
                'password' => Hash::make($default_password)
            ],

            'user_4' => [
                'name' => 'Adam',
                'email' => 'adam@gmail.com',
                'full_name' => 'Adam',
                'phone_number' => Str::random(8),
                'company' => 'Company D',
                'address' => 'Test address 4',
                'password' => Hash::make($default_password)
            ],

            'user_5' => [
                'name' => 'Cecilia',
                'email' => 'cecilia@gmail.com',
                'full_name' => 'Cecilia',
                'phone_number' => Str::random(8),
                'company' => 'Company D',
                'address' => 'Test address 4',
                'password' => Hash::make($default_password)
            ],

            'user_6' => [
                'name' => 'Irfan',
                'email' => 'irfan@gmail.com',
                'full_name' => 'Irfan',
                'phone_number' => Str::random(8),
                'company' => 'Company E',
                'address' => 'Test address 5',
                'password' => Hash::make($default_password)
            ],

            'user_7' => [
                'name' => 'Victor',
                'email' => 'victor@gmail.com',
                'full_name' => 'Victor',
                'phone_number' => Str::random(8),
                'company' => 'Company F',
                'address' => 'Test address 5',
                'password' => Hash::make($default_password)
            ],

            'user_8' => [
                'name' => 'Robert',
                'email' => 'robert@gmail.com',
                'full_name' => 'Robert',
                'phone_number' => Str::random(8),
                'company' => 'Company G',
                'address' => 'Test address 6',
                'password' => Hash::make($default_password)
            ],
        ];

        foreach ($users as $user) {
            // DB::table('users')->insert($user);
            User::create($user);
        }
        //
    }
}
