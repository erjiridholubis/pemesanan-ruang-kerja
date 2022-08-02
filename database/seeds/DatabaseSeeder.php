<?php

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
        $this->call(LevelSeed::class);
        $this->call(WebProfileSeed::class);
        $this->call(UserSeed::class);
    }
}
