<?php

namespace App\Http\Controllers;

use App\DAO\MovieDAO;
use App\Http\Requests\MoviewRequest;
use App\Models\Movie;
use App\Services\MovieService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovieController extends Controller
{
    private $movieService;
    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }
    public function all()
    {
        return response()->json(['data' => $this->movieService->getAllMovies()]);
    }
    public function getActual()
    {
        return response()->json(['data' => $this->movieService->getActualMovies()]);
    }

    public function store(MoviewRequest $request)
    {
        $data = $request->validated();
        $movie = $this->movieService->createMovie($data);
        return response()->json(['data' => $movie], 201);
    }
    public function update(MoviewRequest $request , $id)
    {
        $data = $request->validated();
        $movie = $this->movieService->updateMovie($data, $id);
        if ($movie){
            return response()->json(['data' => $movie], 200);
        }
        return response()->json(['data' => 'not found'], 404);
    }
    public function findById($id)
    {
        $movie = $this->movieService->getMovieById($id);
        if ($movie){
            return response()->json(['data' => $movie], 200);
        }
        return response()->json(['data' => 'not found'], 404);
    }
    public function findByKeyword(Request $request)
    {
        $request->validate([
            'keyword'=>'required'
        ]);
        $movies = $this->movieService->getMoviesByKeyword($request->keyword);
        return response()->json(['data' => $movies], 200);
    }

    public function delete($id)
    {
        $result = $this->movieService->deleteMovie($id);
        if ($result){
            return response()->json(["data" => "deleted"], 204);
        }
        return response()->json(["data" => "not fount"], 404);
    }
}
