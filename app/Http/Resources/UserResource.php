<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'login' => $this->login,
            'email' => $this->email,
            'phone' => $this->phone,
            'name' => $this->name,
            'surname' => $this->surname,
            'totalFiles' => $this->files->count(),
            'totalSize' => $this->files->sum('size'),
            'birthdate' => $this->birthdate,
            'registered' => $this->created_at->format(\DateTimeInterface::W3C),
        ];
    }
}
