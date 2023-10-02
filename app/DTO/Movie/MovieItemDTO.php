<?php


namespace App\DTO;


use App\Models\Movie;

class MovieItemDTO extends MovieDTO
{
    public $sessions = [];
    public function __construct(int $id,string $name, array $sessions)
    {
        parent::__construct($id,$name);
        $this->sessions = $sessions;

    }
}
