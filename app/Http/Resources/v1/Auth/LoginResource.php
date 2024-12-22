<?php

namespace App\Http\Resources\v1\Auth;

use App\DTO\AuthenticatedUserDTO;
use App\Http\Resources\v1\Users\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * LoginResource transforms the authenticated user data and token into a structured response.
 */
class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request The current HTTP request instance.
     * @return array<string, mixed> The structured response array.
     */
    public function toArray(Request $request): array
    {
        /** @var AuthenticatedUserDTO $this **/
        return [
            'user'  => new UserResource($this->getAuthenticatedUser()), // Formats the authenticated user data.
            'token' => $this->getToken(),                              // Includes the authentication token.
        ];
    }
}
