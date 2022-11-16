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
            ],
            [
                'name' => 'John Arian Malondras',
                'username' => 'j.malondras',
                'password' => '$2y$10$MivzruHbhGc0n2HbzQhl3ekKEf9VSF1/jyL075YS78eoy2/rr6tey',
                'department' => 'IT',
                'role' => '0',
            ],
            [
                'name' => 'Angus Oinal',
                'username' => 'a.oinal',
                'password' => '$2y$10$OslWIrXAu4sOFeYUwzPP.utVit3s0Wwaq.JQ5PAgh25iQ72SM6j52',
                'department' => 'Audit',
                'role' => '0',
            ],
        ];

        foreach($accounts as $key => $value){
            Account::create($value);
        }
    }
}
