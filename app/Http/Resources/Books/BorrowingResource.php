<?php

namespace App\Http\Resources\Books;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\Books\BookResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
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
            'borrow_code' => $this->borrow_code,
            'loan_date' => $this->loan_date?->format('Y-m-d'),
            'return_date' => $this->return_date?->format('Y-m-d'),
            'status' => $this->status,
            'user' => new UserResource($this->whenLoaded('user')),
            'book' => new BookResource($this->whenLoaded('book')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
