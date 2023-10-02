<?php


namespace App\Services;


use App\DAO\UserDAO;
use App\DTO\Ticket\TicketDTO;
use App\DTO\UserDTO;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWT;

class UserService
{
    public $userDAO;

    public function __construct(UserDAO $userDAO)
    {
        $this->userDAO = $userDAO;
    }
    public function getAllUsers()
    {
        $users = $this->userDAO->getAllUsers();
        $userDTOs = [];
        foreach ($users as $user) {
            $userDTOs[] = new UserDTO(
                $user->id,
                $user->first_name,
                $user->last_name,
                $user->middle_name,
                $user->emal,
                $user->balance);
        }
        return$userDTOs;
    }
    public function createUser($data)
    {
        $user = $this->userDAO->createUser($data);
        return $user;
    }
    public function getUserById($id)
    {
        return $this->userDAO->getUserById($id);
    }

    public function getAuthUser(){
        $user = $this->userDAO->getUserById(auth()->user()->id);
        return new UserDTO(
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->middle_name,
            $user->email,
            $user->balance
        );
    }

    public function hasBalance($amount)
    {
        $user = JWTAuth::user();
        return $user->balance >= $amount;
    }

    public function increment($amount)
    {
        $user =  JWTAuth::user();
        $user->balance += $amount;
        $user->save();
        return $user;
    }
    public function decrement($amount)
    {
        $user = JWTAuth::user();
        $user->balance -= $amount;
        $user->save();
        return $user;
    }
    public function getTickets()
    {
        $tickets = $this->userDAO->getTickets();
        $ticketsDTOs = [];
        foreach ($tickets as $ticket){
            $ticketsDTOs[] = new TicketDTO(
                $ticket->id,
                $ticket->hall->name,
                $ticket->session->load('movie')->movie->name,
                $ticket->session->load('price')->price->price,
                $ticket->seat->row_number,
                $ticket->seat->seat_number,
                $ticket->session->date
            );
        }
        return $ticketsDTOs;
    }

    public function hasTicket($ticketsId)
    {
        return $this->userDAO->hasTicket($ticketsId);
    }
}
