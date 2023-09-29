<?php


namespace App\DAO;


use App\DTO\UserDTO;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserDAO
{
    public function getAllUsers()
    {
        $users = User::all();

        return $users;
    }
    public function getUserById($id)
    {
        $user = User::findOrfail($id);
        return $user;
    }
    public function createUser($data)
    {
        $user = User::create($data);
        return $user ? new UserDTO(
            $user->id,
            $user->first_name,
            $user->last_name,
            $user->middle_name,
            $user->email,
            $user->balance
        ) : null;
    }
    public function updateUser($id, $data)
    {
        $user = User::findFindOrFail($id);
        $user->update($data);
        return $user;
    }
    public function getTickets()
    {
        $tickets = JWTAuth::user()->tickets()->whereRelation('session', 'date', '>',Carbon::now())->with(['hall','session','seat'])->get();
        return $tickets;
    }

}
