<?php


namespace App\Services;


use App\DAO\SeatDAO;
use App\DAO\SessionDAO;
use App\DAO\TicketDAO;
use App\DTO\CreateTicketDTO;
use App\DTO\TicketDTO;
use App\Http\Resources\TicketResource;
use App\Models\Hall;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketService
{
    private $ticketDAO;
    private $sessioService;
    private $userService;
    private $seatService;

    public function __construct(
        TicketDAO $ticketDAO,
        SessionService $sessionService,
        UserService $userService,
        SeatService $seatService
    )
    {
        $this->ticketDAO = $ticketDAO;
        $this->sessioService = $sessionService;
        $this->userService = $userService;
        $this->seatService = $seatService;
    }
    public function getAllTickets()
    {
        return TicketResource::collection($this->ticketDAO->allTickets());
    }

    public function findTicketById($id)
    {
        $ticket = $this->ticketDAO->getTicketById($id);
        return new TicketResource($ticket);
    }

    public function deleteTicket($id)
    {
        return $this->ticketDAO->deleteTicket($id);
    }

    public function getTotalPrice(CreateTicketDTO $ticketDTO)
    {
        $totalPrice = $this->ticketDAO->getTotalPrice($ticketDTO);
        return $totalPrice;
    }
    public function byTicket(CreateTicketDTO $ticketDTO, $totalPrice)
    {
        $totalPrice = $this->ticketDAO->getTotalPrice($ticketDTO);
         return DB::transaction(function () use($ticketDTO,$totalPrice){
            $result = $this->createTicket($ticketDTO);
            $this->userService->decrement($totalPrice);
            return $result;
        });
    }
    public function returnTickets()
    {

    }

    public function createTicket(CreateTicketDTO $ticketDTO)
    {
        $ticketDTOs = [];
        foreach ($ticketDTO->seats as $item) {
            $data = [
                'user_id' => $ticketDTO->userId,
                'session_id' => $ticketDTO->sessionId,
                'hall_id' => $ticketDTO->hallId,
                'seat_id' => $item
            ];
            $ticket = $this->ticketDAO->createTicket($data);
            $dto = new TicketDTO(
                $ticket->id,
                $ticket->hall->name,
                $ticket->session->movie->name,
                $ticket->session->price->price,
                $ticket->seat->row_number,
                $ticket->seat->seat_number,
                $ticket->session->date
            );
            $ticketDTOs[] = $dto;
        }
        return $ticketDTOs;
    }
}
