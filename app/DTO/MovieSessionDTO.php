<?php


namespace App\DTO;


class MovieSessionDTO extends MovieDTO
{
    public $sessionId;
    public $hallId;
    public $hallName;
    public $date;
    public $price;

    public function __construct(int $id,string $name, int $sessionId,int $hallId,string $hallName,$date,float $price)
    {
        parent::__construct($id,$name);
        $this->sessionId = $sessionId;
        $this->hallId = $hallId;
        $this->hallName = $hallName;
        $this->date = $date;
        $this->price = $price;
    }
}
