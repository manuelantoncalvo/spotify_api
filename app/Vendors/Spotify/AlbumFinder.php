<?php

namespace App\Vendors\Spotify;

class AlbumFinder
{
    const SEARCH = '/v1/search';

    /**
     * @var SpotifyAPI
     */
    protected $spotifyClient;

    public function __construct(SpotifyAPI $spotifyAPI)
    {
        $this->spotifyClient = $spotifyAPI;
    }

    protected function client(): SpotifyAPI
    {
        return $this->spotifyClient;
    }

    public function findByArtistName(string $name): AlbumCollection
    {
        $param = new SearchParam([
            'query' => "artist:$name",
            'objectType' => 'album',
            'requestMethod' => 'GET',
            'options' => ['limit' => 20],
            'uri' => self::SEARCH,
        ]);
        $response = $this->client()->search($param);
        return AlbumCollection::make((array)$response->albums->items)->mapInto(Album::class);
    }
}
