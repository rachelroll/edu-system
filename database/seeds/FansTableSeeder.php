<?php

use Illuminate\Database\Seeder;

class FansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Fan::class, 40)->create();
    }
}
