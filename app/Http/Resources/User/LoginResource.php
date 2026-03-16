<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [
            'user' => new ProfileResource($this),
        ];

        if ($this->accessToken) {
            $response['accessToken'] = $this->accessToken;
        }

        return $response; 
    }
}
