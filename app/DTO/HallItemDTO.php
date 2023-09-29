<?php


namespace App\DTO;


class HallItemDTO extends HallDTO
{
    public $seats = [];
    public $availableSeatsCount;
    public function __construct(int $id, string $name, int $seatCount, array $seats, int $availableSeatsCount)
    {
        parent::__construct($id, $name, $seatCount);
        $this->seats = $seats;
        $this->availableSeatsCount = $availableSeatsCount;
    }
}
