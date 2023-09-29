<?php

namespace Database\Seeders;

use App\Models\Seat;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i<=5; $i++){
            for ($j = 1; $j <= 6; $j++){
                Seat::create([
                    'row_number'=>$i,
                    'seat_number'=>$j
                ]);
            }
        }
    }
}
