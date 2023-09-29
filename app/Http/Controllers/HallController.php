<?php

namespace App\Http\Controllers;

use App\DAO\HallDAO;
use App\Http\Requests\HallRequest;
use App\Models\Hall;
use App\Services\HallService;
use App\Services\SeatService;
use Database\Seeders\HallSeeder;
use Illuminate\Http\Request;

class HallController extends Controller
{
    private $hallService;
    private $seatService;

    public function __construct(HallService $hallService, SeatService $seatService)
    {
        $this->hallService = $hallService;
        $this->seatService = $seatService;
    }
    public function all()
    {
        return response()->json(['data' => $this->hallService->getAllHalls()]);
    }

    public function get($id)
    {
        $hall = $this->hallService->getHallById($id);
        if ($hall){
            return response()->json(['data' => $hall]);
        }
        return response()->json(['data' => 'not found'], 404);
    }
    public function store(HallRequest $request)
    {
        $data = $request->validated();
        $hall = $this->hallService->createHall($data);
        if ($hall){
            return response()->json(['data' => $hall], 201);
        }
        return response()->json(['data'=> 'error'], 400);
    }
    public function update(HallRequest $request, $id)
    {
        $data = $request->validated();
        $hall = $this->hallService->updateHall($data, $id);
        if ($hall){
            return response()->json(['data' => $hall]);
        }
        return response()->json(['data' => 'not found'], 404);
    }
    public function delete($id)
    {
        $result = $this->hallService->deleteHall($id);
        if ($result){
            return response()->json(['data' => 'deleted']);
        }
        return response()->json(['data' => 'not found'], 404);
    }
    public function getSeats($hallId, $sessionId)
    {
        $result = $this->seatService->getSeatsStatus($sessionId,$hallId);
        if (!$result) return response(['data' => 'incorrect data'], 400);
        return response()->json(['data' => $result]);
    }
}
