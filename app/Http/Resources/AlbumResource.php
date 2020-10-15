<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'released' => $this->release_date,
            'tracks' => $this->total_tracks,
            'cover' => $this->images
        ];
    }
}
