<?php

namespace Database\Seeders;

use App\Models\Plante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       Plante::factory()->count(5)->create();
    }
}
