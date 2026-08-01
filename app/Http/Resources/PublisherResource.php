<?php
// app/Http/Resources/PublisherResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PublisherResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'address' => $this->address,
            'logo' => $this->logo_url,
            'website' => $this->website,
            'books_count' => $this->books_count ?? $this->books()->count(),
        ];
    }
}