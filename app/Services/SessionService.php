<?php


namespace App\Services;


use App\DAO\SessionDAO;
use App\DTO\Session\CreateSessionDTO;
use App\DTO\Hall\HallDTO;
use App\DTO\Session\SessionItemDTO;

class SessionService
{
    private $sessionDAO;
    private $seatService;

    public function __construct(SessionDAO $sessionDAO, SeatService $seatService)
    {
        $this->sessionDAO = $sessionDAO;
        $this->seatService = $seatService;
    }
    public function getAllSessions()
    {
       $sessions = $this->sessionDAO->getAllSessions();
       $sessionDTOs = [];
       foreach ($sessions as $session){
           $sessionDTOs[] = $this->convertSessionModelToDTO($session);
        }
       return $sessionDTOs;
    }

    public function findSessionById($id)
    {
        $session =  $this->sessionDAO->getSessionById($id);
        $dto = $this->convertSessionModelToDTO($session);
        return $dto;

    }
    public function updateSession(CreateSessionDTO $sessionDTO, $id)
    {
        $data  = collect($sessionDTO)->toArray();
        $session = $this->sessionDAO->updateSession($data,$id);
        return $this->convertSessionModelToDTO($session);
    }
    public function createSession(CreateSessionDTO $sessionDTO)
    {
        $data  = collect($sessionDTO)->toArray();
        $session = $this->sessionDAO->createSession($data);
        return $this->convertSessionModelToDTO($session);
    }

    public function deleteSession($id)
    {
        $result = $this->sessionDAO->deleteSession($id);
        return $result;
    }
    public function getSessionMaxSeatsCount($sessionId)
    {
        return $this->sessionDAO->getSessionMaxSeatsCount($sessionId);
    }

    private function convertSessionModelToDTO($model)
    {
        $hallsDTOs = [];
        foreach ($model->halls as $hall){
            $hallsDTOs[] = new HallDTO($hall->id, $hall->name,$hall->seat_count);
        }
        $dto = new SessionItemDTO(
            $model->id,
            $model->movie->name,
            $model->date,
            $model->price->price,
            $hallsDTOs
        );
        return $dto;
    }
    public function isActual($sessionId)
    {
        return $this->sessionDAO->isActual($sessionId);
    }
    public function hasHall($sessionId, $hallId)
    {
        return $this->sessionDAO->hasHall($sessionId, $hallId);
    }
}
