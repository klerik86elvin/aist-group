<?php

namespace App\Http\Controllers;

use App\DTO\CreateTicketDTO;
use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\Session;
use App\Models\Ticket;
use App\Services\SeatService;
use App\Services\TicketService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class TicketController extends Controller
{
    private $ticketService;
    private $userService;
    private $seatService;

    public function __construct(TicketService $ticketService, UserService $userService, SeatService $seatService)
    {
        $this->ticketService = $ticketService;
        $this->userService = $userService;
        $this->seatService = $seatService;
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
            return response()->json(['data' => 'error'], 400);
        }
        return response()->json($ticket);
    }
}
