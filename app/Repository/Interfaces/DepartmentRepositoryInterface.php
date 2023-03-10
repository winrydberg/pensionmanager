<?php
namespace App\Repository\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface DepartmentRepositoryInterface
{
   public function getAllDepartments(): Collection;

   public function createDepartment(array $departmentDetails): ?Model;

   public function getDepartmentById($departmentId): ?Model;

}