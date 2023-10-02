<?php


namespace App\DTO\Hall;


class HallDTO
{
    public $id;
    public $name;
    public $seatCount;

    public function __construct(int $id, string $name,int $seatCount)
    {
        $this->id = $id;
        $this->name = $name;
        $this->seatCount = $seatCount;
    }
}
