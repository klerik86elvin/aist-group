<?php

namespace App\Http\Controllers;

use App\DTO\Session\CreateSessionDTO;
use App\Http\Requests\SessionRequest;
use App\Services\SessionService;
class SessionController extends Controller
{
    private $sessionService;

    public function __construct(SessionService $service)
    {
        $this->sessionService = $service;
    }
    public function all()
    {
        return $this->sessionService->getAllSessions();
    }
    public function get($id)
    {
        $session = $this->sessionService->findSessionById($id);
        return response()->json(['data' => $session]);
    }
    public function update(SessionRequest $request,$id)
    {
        $sessioDto = new CreateSessionDTO(
            $request->validated('movie_id'),
            $request->validated('date'),
            $request->validated('price_rule_id'),
            $request->validated('halls'),
        );
        $session = $this->sessionService->updateSession($sessioDto, $id);
        return response()->json(['data' => $session]);
    }

    public function store(SessionRequest $request)
    {
        $sessioDto = new CreateSessionDTO(
            $request->validated('movie_id'),
            $request->validated('date'),
            $request->validated('price_rule_id'),
            $request->validated('halls'),
        );
        $session = $this->sessionService->createSession($sessioDto);
        return response()->json(['data' => $session], 201);
    }
    public function delete($id)
    {
        $result = $this->sessionService->deleteSession($id);
        if ($result){
            return response()->json(['data' => 'deleted'], 204);
        }
    }
}
