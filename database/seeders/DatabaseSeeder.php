<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlacementQuestion;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(PlacementQuestionsSeeder::class);
    }
}
