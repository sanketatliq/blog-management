<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginatedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_page' => $this->currentPage(),
            'last_page' => $this->lastPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'next_page_url' => $this->nextPageUrl(),
            'total' => $this->total(),
            'from' => $this->firstItem(),
            'to' => $this->lastItem(),
            'per_page' => $this->perPage(),
            'first_page_url' => $this->url(1),
            'last_page_url' => $this->url($this->lastPage()),
            'path' => $this->path(),
            'blogs' => DetailResource::collection($this->resource),
        ];
    }
}
