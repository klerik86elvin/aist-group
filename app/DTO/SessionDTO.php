<?php


namespace App\DTO;


class SessionDTO
{
    public $id;
    public $movieName;
    public $date;
    public $price;

    public function __construct(int $id,string $movieName, $date, float $price)
    {
        $this->id = $id;
        $this->movieName = $movieName;
        $this->price = $price;
        $this->date = $date;
    }
}
