<?php


namespace App\Services;


use App\DAO\HallDAO;
use App\DTO\Hall\HallDTO;

class HallService
{
    private $hallDAO;
    private $seatService;
    public function __construct(HallDAO $hallDAO, SeatService $seatService)
    {
        $this->hallDAO = $hallDAO;
        $this->seatService = $seatService;
    }
    public function getAllHalls()
    {
        $halls = $this->hallDAO->getAllHalls();
        $hallDTOs = [];
        foreach ($halls as $hall){
            $hallDTOs[] = $this->convertHallModelToHallDTO($hall);
        }
        return $hallDTOs;
    }
    public function createHall($data)
    {
        $hall = $this->hallDAO->createHall($data);
        return $hall;
        return $this->convertHallModelToHallDTO($hall);
    }
    public function updateHall($data,$id)
    {
        $hall = $this->hallDAO->updateHall($data, $id);
        return $this->convertHallModelToHallDTO($hall);
    }
    public function getHallById($id)
    {
        $hall = $this->hallDAO->getHallById($id);
        return $this->convertHallModelToHallDTO($hall);
    }
    public function deleteHall($id)
    {
        return $this->hallDAO->deleteHall($id);

    }
    private function convertHallModelToHallDTO($hall)
    {
        $movieDTO = new HallDTO($hall->id,$hall->name,$hall->seat_count);
        return $movieDTO;
    }
}
