<?php

namespace App\Http\Controllers;

use App\DTO\Ticket\CreateTicketDTO;
use App\Http\Requests\TicketRequest;
use App\Services\SeatService;
use App\Services\SessionService;
use App\Services\TicketService;
use App\Services\UserService;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketController extends Controller
{
    private $ticketService;
    private $userService;
    private $seatService;
    private $sessionService;

    public function __construct(
        TicketService $ticketService,
        UserService $userService,
        SeatService $seatService,
        SessionService $sessionService
    )
    {
        $this->middleware(['auth:api'])->only('byTicket');
        $this->ticketService = $ticketService;
        $this->userService = $userService;
        $this->seatService = $seatService;
        $this->sessionService = $sessionService;
    }

    public function all()
    {
        $tickets = $this->ticketService->getAllTickets();
        return response()->json(['data' => $tickets]);
    }
    public function byTicket(TicketRequest $request)
    {
        try{
            $user = JWTAuth::user();
            $ticketDTO = new CreateTicketDTO(
                $user->id,
                $request->validated('session_id'),
                $request->validated('hall_id'),
                $request->validated('seats')
            );
            if (!$this->sessionService->hasHall($ticketDTO->sessionId,$ticketDTO->hallId)){
                return response()->json(['data' => 'this session does not exist in this hall'],400);
            };
            if (!$this->sessionService->isActual($ticketDTO->sessionId)){
                return response()->json(['data' => 'session time cannot be earlier than current time'],400);
            }
            $totalPrice = $this->ticketService->getTotalPrice($ticketDTO);
            if (!$this->userService->hasBalance($totalPrice)){
                return response()->json(['data'=> 'not enough balance'],400);
            }
            if (!$this->seatService->availableSeats($ticketDTO)){
                return response()->json(['data' => 'Seats are taken'], 400);
            }
            $ticket = $this->ticketService->byTicket($ticketDTO,$totalPrice);
        }
        catch (\Exception $exception){
            return response()->json(['data' => $exception], 500);
        }
        return response()->json($ticket);
    }
}
