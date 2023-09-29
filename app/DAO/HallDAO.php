<?php


namespace App\DAO;


use App\DTO\HallDTO;
use App\Models\Hall;

class HallDAO
{
    public function getAllHalls()
    {
        $halls = Hall::all();
        return $halls;
    }

    public function getHallById($id)
    {
        $hall = Hall::findOrFail($id);

        return $hall;
    }
    public function createHall($data)
    {
        $hall = Hall::create($data);
        return $hall;
    }

    public function updateHall($data, $id)
    {
        $hall = Hall::findOrFail($id);
        $hall->update($data);
        return $hall;
    }

    public function deleteHall($id)
    {
        $hall = Hall::findOrFail($id);
        return $hall->delete();
    }
}
