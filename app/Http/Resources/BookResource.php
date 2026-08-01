<?php
// app/Http/Resources/BookResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
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
            'cover' => $this->cover_url,
            'price' => $this->price,
            'discount' => $this->discount,
            'final_price' => $this->final_price,
            'is_free' => $this->is_free,
            'is_featured' => $this->is_featured,
            'is_discounted' => $this->is_discounted,
            'discount_percentage' => $this->discount_percentage,
            'average_rating' => (float) $this->average_rating,
            'total_ratings' => $this->total_ratings,
            'language' => $this->language,
            'pages' => $this->pages,
            'category' => $this->whenLoaded('category', function() {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                ];
            }),
            'author' => $this->whenLoaded('author', function() {
                return [
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                    'slug' => $this->author->slug,
                ];
            }),
            'publisher' => $this->whenLoaded('publisher', function() {
                return [
                    'id' => $this->publisher->id,
                    'name' => $this->publisher->name,
                    'slug' => $this->publisher->slug,
                ];
            }),
            'is_purchased' => $this->when(isset($this->is_purchased), $this->is_purchased, false),
            'can_download' => $this->when(isset($this->can_download), $this->can_download, false),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}