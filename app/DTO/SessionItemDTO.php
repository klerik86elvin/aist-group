<?php


namespace App\DTO;


use App\DTO\SessionDTO;

class SessionItemDTO extends SessionDTO
{
    public $halls = [];
    public function __construct(int $id, string $movieName, $date, float $price, array $halls)
    {
        parent::__construct($id, $movieName, $date, $price);
        $this->halls = $halls;
    }
}
