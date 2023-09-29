<?php


namespace App\DTO;


class CreateTicketDTO
{
    public $userId;
    public $sessionId;
    public $hallId;
    public $seats;

    public function __construct(int $userId, int $sessionId, int $hallId, array $seats)
    {
        $this->userId = $userId;
        $this->sessionId = $sessionId;
        $this->seats = $seats;
        $this->hallId = $hallId;
    }
}
