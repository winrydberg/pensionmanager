<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Get Authenticated User
     */
    public function getAuthUser(): Model 
    {
        return Auth::user()->with('department')->first();
    }

    /**
     * gets all users/ staff
     */
    public function getAllUsers():Collection
    {
        return User::with('department', 'roles')->get();
    }

    /**
     * Gte user record by id
     */
    public function getUserById($userId): ?Model
    {
        return User::find($userId)->with('roles','permissions')->first();
    }

    /**
     * create a new user /staff
     */
    public function createUser(array $userDetails): array
    {
         if(array_key_exists('email', $userDetails)){
            $user = User::where('email', $userDetails['email'])->first();
            if($user){
                return [
                    'status' => 'error',
                    'message' => 'Staff with email '.$userDetails['email'].' already exists'
                ];
            }
         }
        $userDetails['password'] = Hash::make($userDetails['password']);
        $user = User::create($userDetails);

        if(array_key_exists('permissions', $userDetails)){
            foreach($userDetails['permissions'] as $p){
                $permission = Permission::find($p);
                if($permission){
                    $user->givePermissionTo($permission->name);
                }
            }
        }

        if(array_key_exists('roles', $userDetails)){
            foreach($userDetails['roles'] as $r){
                $role = Role::find($r);
                if($role){
                   $user->assignRole($role->name);
                }
            }
        }

        return [
            'status' => 'success',
            'user' => $user,
            'message' => 'Staff account successfully created'
        ];
    }

    /**
     * Updates a particular user
     */
    public function updateUser($userId, array $newDetails) :?Model
    {
        return User::whereId($userId)->update($newDetails);
    } 

    /**
     * deletes a user
     */
    public function deleteUser($userId)
    {
        User::destroy($userId);
    }


}