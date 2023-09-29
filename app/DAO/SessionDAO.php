<?php


namespace App\DAO;


use App\DTO\SessionDTO;
use App\Models\Session;

class SessionDAO
{
    public function createSession($data)
    {
        $session = Session::create($data);
        $session->halls()->attach($data['halls']);
        if ($session){
            return $session;
        }
        return null;
    }

    public function getAllSessions()
    {
        $sessions =  Session::with(['movie','halls','price'])->get();
        return $sessions;
    }

    public function getSessionById($id)
    {
        $session = Session::findOrFail($id);
        return $session;
    }
    public function updateSession($data ,$id)
    {
        $session = Session::findOrFail($id);
        $session->halls()->sync($data['halls']);
        $session->update($data);

        return $session;
    }
    public function deleteSession($id)
    {
        $session = Session::findOrFail($id);
        return $session->delete();
    }

    public function getSessionsByMovieId($id)
    {
        $sessions = Session::where('movie_id', $id)->get();
        return $sessions;
    }
    public function getSessionMaxSeatsCount($sessionId)
    {
        $count = Session::withOut(['movie','price'])->find($sessionId)->hall->seat_count;
        return $count;
    }
}
