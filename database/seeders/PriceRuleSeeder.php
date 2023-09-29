<?php

namespace Database\Seeders;

use App\Models\PriceRule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PriceRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ["name" => "afternoon session", "price" => 11.35],
            ["name" => "night session", "price" => 13.75]
        ];

        foreach ($data as $d){
            PriceRule::create($d);
        }
    }
}
