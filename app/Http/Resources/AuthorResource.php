<?php
// app/Http/Resources/AuthorResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'bio' => $this->bio,
            'image' => $this->image_url,
            'website' => $this->website,
            'books_count' => $this->books_count ?? $this->books()->count(),
        ];
    }
}