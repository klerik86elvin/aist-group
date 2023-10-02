<?php


namespace App\DTO;


use App\Models\Session;

class CreateSessionDTO
{
    public $movie_id;
    public $date;
    public $price_rule_id;
    public $halls = [];

    public function __construct(int $movie_id, $date, $price_rule_id, array $halls)
    {
        $this->movie_id = $movie_id;
        $this->date = $date;
        $this->price_rule_id = $price_rule_id;
        $this->halls = $halls;
    }
}
