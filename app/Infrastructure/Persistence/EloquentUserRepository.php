<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User as DomainUser;
use App\Models\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): DomainUser
    {
        $elUser = EloquentUser::findOrFail($id);
        return $this->toDomain($elUser);
    }

    public function findByEmail(string $email): ?DomainUser
    {
        $elUser = EloquentUser::where('email', $email)->first();

        if (!$elUser) {
            return null;
        }

        return $this->toDomain($elUser);
    }

    public function save(DomainUser $domainUser): DomainUser
    {
        // Find if the user exists first to preserve the name
        $existingUser = EloquentUser::where('email', $domainUser->email)->first();

        $elUser = EloquentUser::updateOrCreate(
            ['email' => $domainUser->email],
            [
                'password' => $domainUser->getPasswordHash(),
                // Only set a default name if the user doesn't already have one
                'name' => $existingUser->name ?? explode('@', $domainUser->email)[0],
            ]
        );
        // If this shows "wasRecentlyCreated => false",
        // it means it didn't actually hit the DB.
        // dd($elUser->toArray());

        return $this->toDomain($elUser);
    }

    /**
     * Helper to map Eloquent Model to Domain Entity
     */
    private function toDomain(EloquentUser $elUser): DomainUser
    {
        // Using getAuthPassword() ensures we get the actual hash
        // Laravel is using for authentication, regardless of $hidden settings.
        return new DomainUser(
            $elUser->id,
            $elUser->email,
            $elUser->getAuthPassword()
        );
    }
}
