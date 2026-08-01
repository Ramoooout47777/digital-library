<?php
// app/Http/Resources/BookDetailResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'cover' => $this->cover_url,
            'pdf' => $this->when($this->can_download, $this->pdf_url),
            'sample_pdf' => $this->sample_pdf_url,
            'price' => $this->price,
            'discount' => $this->discount,
            'final_price' => $this->final_price,
            'formatted_price' => $this->formatted_price,
            'formatted_final_price' => $this->formatted_final_price,
            'is_free' => $this->is_free,
            'is_featured' => $this->is_featured,
            'is_discounted' => $this->is_discounted,
            'discount_percentage' => $this->discount_percentage,
            'average_rating' => (float) $this->average_rating,
            'total_ratings' => $this->total_ratings,
            'language' => $this->language,
            'pages' => $this->pages,
            'isbn' => $this->isbn,
            'file_size' => $this->file_size,
            'stock' => $this->stock,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'publisher' => new PublisherResource($this->whenLoaded('publisher')),
            'metadata' => $this->metadata,
            'is_purchased' => $this->when(isset($this->is_purchased), $this->is_purchased, false),
            'can_download' => $this->when(isset($this->can_download), $this->can_download, false),
            'is_in_favorite' => $this->when(isset($this->is_in_favorite), $this->is_in_favorite, false),
            'published_at' => $this->published_at?->toISOString(),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}