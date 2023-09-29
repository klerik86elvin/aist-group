<?php


namespace App\Services;


use App\DAO\MovieDAO;
use App\DTO\MovieDTO;
use App\DTO\MovieItemDTO;
use App\DTO\MovieSessionDTO;
use App\Http\Resources\MovieResource;

class MovieService
{
    private $movieDAO;
    private $sessionService;
    public function __construct(MovieDAO $movieDAO, SessionService $sessionService)
    {
        $this->movieDAO = $movieDAO;
        $this->sessionService = $sessionService;
    }

    public function getAllMovies()
    {
       $movieDTOs = [];
       $movies = $this->movieDAO->getAllMovies();
        foreach ($movies as $item) {
            $movieDTOs[] = new MovieDTO($item->id,$item->name);
       }
        return$movieDTOs;
    }
    public function getActualMovies()
    {
        $moviesDTOs = [];
        $movies = $this->movieDAO->getActualMovies();
        foreach ($movies as $movie){
            $moviesDTOs[] = new MovieDTO($movie->movie_id,$movie->movie_name);
        }
        return $moviesDTOs;
    }
    public function getMovieById(int $id)
    {
        $movies = $this->movieDAO->getActualMovieDetail($id);
        $movieSessionDTOs = $this->convertMovieSessionsModelToDTO($movies);
        return $movieSessionDTOs;
    }
    public function createMovie($data)
    {
        $movie = $this->movieDAO->createMovie($data);
        return $movie;
    }
    public function getMoviesByKeyword(string $value)
    {
        $moviesDTOs = $this->convertMovieSessionsModelToDTO($this->movieDAO->getMoviesByKeyword($value));
        return $moviesDTOs;
    }
    public function updateMovie($data, int $id)
    {
        return new MovieResource($this->movieDAO->updateMovie($data,$id));
    }
    public function deleteMovie($id)
    {
        return $this->movieDAO->deleteMovie($id);
    }

    private function convertMovieSessionsModelToDTO($sessions)
    {
        $movieSessionDTOs = [];
        foreach ($sessions as $session){
            $dto = new MovieSessionDTO(
                $session->movie_id,
                $session->movie_name,
                $session->session_id,
                $session->hall_id,
                $session->hall_name,
                $session->date,
                $session->price
            );
            $movieSessionDTOs[] = $dto;
        }
        return$movieSessionDTOs;
    }
}
