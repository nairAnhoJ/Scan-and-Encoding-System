<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $accounts = [
            [
                'name' => 'Administrator',
                'username' => 'saes.admin',
                'password' => '$2y$10$.rnvTPzupHdrYSbmV4wWdeF5Y6cHjo0TvX3asRH3t6UBWrt.M6/GG',
                'department' => 'ADMIN',
                'role' => '1',
            ]
        ];

        foreach($accounts as $key => $value){
            Account::create($value);
        }
    }
}
