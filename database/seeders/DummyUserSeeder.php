<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; 
class DummyUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() 
    { 
        User::create([ 
            'name' => 'Admin', 
            'email' => 'evelyn@example.com', 
            'password' => Hash::make('evelyn123') 
        ]); 
    }
}
