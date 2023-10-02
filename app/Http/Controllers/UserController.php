<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    private $userService;
    private $ticketService;

    public function __construct(UserService $userService, TicketService $ticketService)
    {
        $this->middleware(['auth:api']);
        $this->userService = $userService;
        $this->ticketService = $ticketService;
    }


    public function getTickets()
    {
        return response()->json(['data' => $this->userService->getTickets()]);
    }
    public function returnTicket($id)
    {
        if($this->ticketService->expired($id)){
            return response()->json(['data' =>'the ticket can be returned up to 5 hours before the show']);
        };
        if (!$this->userService->hasTicket($id)){
            return response()->json(['data' => 'the ticket does not belong to this user'], 400);
        }
        try {
            $this->ticketService->returnTickets($id);
            return response()->json(['data' => 'deleted']);
        }
        catch (\Exception $exception){
            return response()->json(['data' => $exception->getMessage()],500);
        }

    }
}
