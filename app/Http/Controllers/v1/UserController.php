<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Users\StoreUserRequest;
use App\Http\Requests\v1\Users\UpdateUserRequest;
use App\Http\Resources\v1\Users\UserResource;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

/**
 * UserController handles CRUD operations for users.
 * It leverages the UserRepository for database interactions
 * and uses custom request classes for validation and resource classes for formatting responses.
 */
class UserController extends Controller
{
    /**
     * Constructor to inject the UserRepository.
     *
     * @param UserRepository $userRepository Handles user-related database operations.
     */
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    /**
     * Get all users with pagination.
     *
     * @return JsonResponse A paginated list of users formatted with meta information.
     */
    public function index(): JsonResponse
    {
        return response()->success(
            UserResource::collection($this->userRepository->paginate()) // Fetch paginated users.
            ->response() // Convert resource collection to a response.
            ->getData(true) // Include meta information like pagination details.
        );
    }

    /**
     * Get a specific user by ID.
     *
     * @param User $user The user to retrieve.
     * @return JsonResponse The user data in a structured format.
     */
    public function show(User $user): JsonResponse
    {
        return response()->success(new UserResource($user));
    }

    /**
     * Create a new user.
     *
     * @param StoreUserRequest $request Validated user creation data.
     * @return JsonResponse The created user data with a success message.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        return response()->success(
            new UserResource($this->userRepository->create($request->validated())), // Create user.
            'User created successfully', // Success message.
            201 // HTTP status for resource creation.
        );
    }

    /**
     * Update an existing user.
     *
     * @param UpdateUserRequest $request Validated user update data.
     * @param User $user The user to update.
     * @return JsonResponse The updated user data with a success message.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->userRepository->update($user->id, $request->validated()); // Update user in the repository.
        return response()->success(
            new UserResource($this->userRepository->getById($user->id)), // Retrieve updated user.
            'User updated successfully' // Success message.
        );
    }

    /**
     * Delete a user.
     *
     * @param User $user The user to delete.
     * @return JsonResponse An empty response with a success message.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->userRepository->delete($user->id); // Delete user from the repository.
        return response()->success([], 'User deleted successfully'); // Respond with a success message.
    }
}
