<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user with validation.
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function createUser(array $data): User
    {
        $validator = Validator::make($data, [
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users')->where(function ($query) use ($data) {
                    return $query->where('location_id', $data['location_id']);
                }),
            ],
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->userRepository->create($validator->validated());
    }

    /**
     * Update an existing user with validation.
     *
     * @param User $user
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function updateUser(User $user, array $data): User
    {
        $validator = Validator::make($data, [
            'email' => [
                'required',
                'email',
                \Illuminate\Validation\Rule::unique('users')->where(function ($query) use ($data) {
                    return $query->where('location_id', $data['location_id']);
                })->ignore($user->id),
            ],
            'location_id' => 'required|exists:locations,id',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $this->userRepository->update($user, $validator->validated());
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool
     */
    public function deleteUser(User $user): bool
    {
        return $this->userRepository->delete($user);
    }

    /**
     * Search users with filters for email, location, and status.
     *
     * @param string|null $email
     * @param int|null $locationId
     * @param string|null $status
     * @return LengthAwarePaginator
     */
    public function searchUsers(?string $email = null, ?int $locationId = null, ?string $status = null): LengthAwarePaginator
    {
        return $this->userRepository->search($email, $locationId, $status);
    }
}
