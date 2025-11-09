<?php

namespace App\Http\Resources\Books;

use Illuminate\Http\Request;
use App\Http\Resources\Books\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'author' => $this->author,
            'number_book' => $this->number_book,
            'publisher' => $this->publisher,
            'cover' => $this->cover,
            'publication_year' => $this->publication_year,
            'stock' => $this->stock,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

    }
}
