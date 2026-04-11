<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Domain\Entities\User as DomainUser;
use App\Models\User as MongoUser;

class MongoUserRepository implements UserRepositoryInterface{
    public function findByEmail(string $email): ?DomainUser
    {
        $record = MongoUser::where('email', $email)->first();
        return $record ? $this->toDomain($record) : null;
    }
    public function findById(int $id): DomainUser
    {
        $elUser = MongoUser::findOrFail($id);
        return $this->toDomain($elUser);
    }
    public function save(DomainUser $user): DomainUser
    {
        // MongoDB specific save logic (e.g., using collections)
    }

    private function toDomain($record): DomainUser
    {
        // Map MongoDB BSON/Array to your DomainUser Entity
    }
}
