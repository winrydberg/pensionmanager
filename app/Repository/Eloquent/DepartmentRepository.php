<?php

namespace App\Repository\Eloquent;

use App\Models\Department;
use App\Repository\Interfaces\DepartmentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class DepartmentRepository implements DepartmentRepositoryInterface
{


    /**
    * @return Collection
    */
   public function getAllDepartments(): Collection
   {
       return Department::all();    
   }

   public function createDepartment(array $dptDetails): ?Model
   {
        return Department::create($dptDetails);
   }

   public function getDepartmentById($departmentId):Model{
        return Department::findOrFail($departmentId);
   }
}