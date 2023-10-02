<?php


namespace App\DTO\Movie;

class MovieDTO
{
    public $id;
    public $name;

    public function __construct(int $id,string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}
