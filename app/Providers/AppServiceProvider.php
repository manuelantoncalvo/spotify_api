<?php

namespace App\Providers;

use App\Vendors\Spotify\SpotifyAPI;
use Illuminate\Support\ServiceProvider;
use SebastianBergmann\Diff\ConfigurationException;

class AppServiceProvider extends ServiceProvider
{
    
    public function boot()
    {
        $this->checkEnvConfiguration();
        
        $this->app->singleton(SpotifyAPI::class, function ($app) {
            return new SpotifyAPI();
        });
    }
    
    protected function checkEnvConfiguration()
    {
        if (is_null(env('SPOTIFY_CLIENT_ID')) || is_null(env('SPOTIFY_CLIENT_SECRET'))) {
            throw new \Exception("SPOTIFY_CLIENT_ID and SPOTIFY_CLIENT_SECRET configs vars in .env file is required!");
        }
    }
    
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    
    }
}
