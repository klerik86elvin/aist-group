<?php


namespace App\DTO;


use App\Models\Hall;
use function Nette\Utils\send;

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
