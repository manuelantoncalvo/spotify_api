<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlbumResource;
use App\Vendors\Spotify\AlbumFinder;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * @var AlbumFinder
     */
    protected $albumFinder;
    
    public function __construct(AlbumFinder $albumFinder)
    {
        $this->albumFinder = $albumFinder;
    }
    
    public function index(Request $request)
    {
        $albumCollection = $this->albumFinder->findByArtistName($request->query('q', ''));
        return response(AlbumResource::collection($albumCollection), 200);
    }
    
}
