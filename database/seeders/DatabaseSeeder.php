<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory()->create([
             'name' => 'Testello',
             'email' => 'testello@uello.com',
         ]);

         Customer::factory()
             ->count(100)
             ->create();
    }
}
