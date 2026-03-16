<?php

namespace App\Http\Resources\Blog;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'image'       => $this->getFirstMedia('blog_images') ? $this->getFirstMediaUrl('blog_images') : null,
            'like_count'  => $this->likes_count ?? $this->likes()->count(),
            'is_liked'    => (bool) ($this->is_liked ?? false),
            'created_by'  => $this->creator?->only('id', 'first_name', 'last_name', 'email'),
            'created_at'  => $this->created_at,
        ];
    }
}
