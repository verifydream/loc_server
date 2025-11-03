<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository
{
    /**
     * Find a user by email with location relationship.
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::with('location')
            ->where('email', strtolower($email))
            ->first();
    }

    /**
     * Search users with filters for email, location_id, and status.
     *
     * @param string|null $email
     * @param int|null $locationId
     * @param string|null $status
     * @return LengthAwarePaginator
     */
    public function search(?string $email = null, ?int $locationId = null, ?string $status = null): LengthAwarePaginator
    {
        $query = User::with('location');

        if ($email) {
            $query->where('email', 'like', '%' . strtolower($email) . '%');
        }

        if ($locationId) {
            $query->where('location_id', $locationId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Create a new user.
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Update an existing user.
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh('location');
    }

    /**
     * Delete a user.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}
