<?php


namespace App\DAO;


use App\DTO\MovieDTO;
use App\Models\HallSession;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class MovieDAO
{
    public function createMovie($data)
    {
        $movie =  Movie::create($data);
        return $movie ? : null;
    }
    public function getMovieById(int $id)
    {

        $movie =  Movie::with('sessions')->findOrFail($id);
        return $movie;
    }
    public function getAllMovies()
    {
        $movies = Movie::all();
        return $movies;
    }
    public function getMoviesByKeyword(string $value)
    {
        $movies = Movie::where('name', 'like',$value.'%')->get();
        return $movies;
    }
    public function updateMovie($data, int $id)
    {
        $movie =  Movie::findOrFail($id);
        $movie->update($data);
        return  $movie;
    }
    public function deleteMovie($id)
    {
        $movie = Movie::findOrFail($id);
        return $movie->delete();
    }
    public function getActualMovies()
    {
        $data = HallSession::leftJoin('tickets', function($join) {
            $join->on('hall_session.session_id', '=', 'tickets.session_id')
                ->on('hall_session.hall_id', '=', 'tickets.hall_id');
        })
            ->leftJoin('sessions as s', 'hall_session.session_id', '=', 's.id')
            ->leftJoin('halls as h', 'hall_session.hall_id', '=', 'h.id')
            ->leftJoin('movies as m', 's.movie_id', '=', 'm.id')
            ->groupBy(
                'h.seat_count',
                'm.name',
                'm.id',
            )
            ->havingRaw('COUNT(tickets.id) < h.seat_count')
            ->selectRaw('m.id as movie_id,m.name as movie_name,COUNT(tickets.id) as tickets')
            ->get();

        return $data;
    }
    public function getActualMovieDetail($movieId)
    {
        $data = HallSession::leftJoin('tickets', function($join) {
            $join->on('hall_session.session_id', '=', 'tickets.session_id')
                ->on('hall_session.hall_id', '=', 'tickets.hall_id');
        })
            ->leftJoin('sessions as s', 'hall_session.session_id', '=', 's.id')
            ->leftJoin('halls as h', 'hall_session.hall_id', '=', 'h.id')
            ->leftJoin('movies as m', 's.movie_id', '=', 'm.id')
            ->leftJoin('price_rules as pr', 'pr.id','=', 's.price_rule_id')
            ->groupBy(
                'h.seat_count',
                'm.name',
                'm.id',
                's.id',
                'h.id',
                's.date',
                'pr.price',
                'h.name',
                'm.name'
            )
            ->havingRaw('COUNT(tickets.id) < h.seat_count')
            ->selectRaw('m.id as movie_id,m.name as movie_name, s.id as session_id,h.id as hall_id, h.name as hall_name, s.date as date, pr.price as price')
            ->where('m.id', $movieId)
            ->get();

        return $data;
    }
}
