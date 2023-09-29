<?php


namespace App\DTO;


class TicketDTO
{
    public $id;
    public $hallName;
    public $movieName;
    public $price;
    public $rowNumber;
    public $seatNumber;
    public $date;

    public function __construct(
        int $id,
        string $hallName,
        string $movieName,
        float $price,
        int $rowNumber,
        int $seatNumber,
        $date
)
    {
        $this->id = $id;
        $this->hallName = $hallName;
        $this->movieName = $movieName;
        $this->price = $price;
        $this->rowNumber = $rowNumber;
        $this->seatNumber = $seatNumber;
        $this->date = $date;
    }
}
