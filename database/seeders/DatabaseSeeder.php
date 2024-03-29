<?php

namespace Database\Seeders;

use App\Models\User;
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
        $user = new User(['name' => 'Anonymous', 'email' => 'anonymous@is.legion', 'password' => 'password']);
        $user->save();
    }
}
