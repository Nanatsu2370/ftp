<?php

use App\Category;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Using for's instead of amounts because it fails to adjust indexes when used amounts.
     * Multiple calls required for adjusting
     * @return void
     */
    public function run()
    {
        for ($i=0; $i < 5; $i++) {
            factory(Category::class)->state('root')->create();
        }
        for ($i=0; $i < 15; $i++) {
            factory(Category::class)->state('node')->create();
        }
    }
}
