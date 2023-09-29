<?php


namespace App\DTO;


class SeatDTO
{
    public $id;
    public $rowNumber;
    public $seatNumber;
    public $isAvailable;

    public function __construct(int $id, int $rowNumber, int $seatNumber, bool $isAvailable)
    {
        $this->id = $id;
        $this->rowNumber = $rowNumber;
        $this->seatNumber = $seatNumber;
        $this->isAvailable = $isAvailable;
    }
}
