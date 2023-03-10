<?php
namespace App\Repository\Interfaces;

use App\Model\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
   public function getAllUsers(): Collection;

   public function createUser(array $userDetails): array;

   public function getUserById($userId): ?Model;

   public function updateUser(int $userId, array $newDetails): ?Model;

   public function deleteUser($userId);

   public function getAuthUser(): ?Model;

   // public function assignRole($userId, $role);
}