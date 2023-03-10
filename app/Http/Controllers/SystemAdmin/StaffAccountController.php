<?php

namespace App\Http\Controllers\SystemAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewStaffFormRequest;
use App\Models\Department;
use App\Models\User;
use App\Repository\Eloquent\DepartmentRepository;
use Illuminate\Http\Request;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\Interfaces\DepartmentRepositoryInterface;
use App\Repository\Interfaces\EloquentRepositoryInterface;
use App\Repository\Interfaces\SchemeRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class StaffAccountController extends Controller
{
    private UserRepositoryInterface $userRepository;
    private DepartmentRepositoryInterface  $deptRepository;
    private SchemeRepositoryInterface $schemeRepo;

    public function __construct(UserRepositoryInterface $userRepository, DepartmentRepositoryInterface $deptRepository, SchemeRepositoryInterface $schemeRepo)
    {
        $this->userRepository = $userRepository;
        $this->deptRepository = $deptRepository;
        $this->schemeRepo = $schemeRepo;
    }

    /**
     * REGISTER NEW STAFF
     */
    public function newStaff(){
        $departments = $this->deptRepository->getAllDepartments();
        $roles = Role::all();
        $permissions = Permission::all();
        $schemes = $this->schemeRepo->getSchemes();
        return view('systemadmins.newstaff', compact('departments', 'roles', 'schemes', 'permissions'));
    }

    /**
     * SAVE NEW STAFF
     */
    public function saveNewStaff(Request $request){
        // dd($request->all());
        // return;
        try{
            $validated = $request->all();
            $result = $this->userRepository->createUser($validated);
            return redirect()->back()->with($result['status'], $result['message']);
        }catch(Exception $e){
            Log::error("=========START Staff Creation Erro=========");
            Log::error($e->getMessage());
            Log::error("=========END Staff Creation Erro=========");
            return redirect()->back()->with('error', 'Ooops, Unable to create staff account. Please try again');
        }
    }

    /**
     * GET ALL STAFFS
     */
    public function allStaffs(){
        $staffs = $this->userRepository->getAllUsers();
        // $roles = Role::all();
        return view('systemadmins.staffs', compact('staffs'));
    }


    /**
     * edit staff
     */
    public function editStaff(Request $request){
        $staffId = $request->query('staffid', null);

        if($staffId == null){
            return back()->with('error', 'Staff Not Found');
        }else{
            $departments = $this->deptRepository->getAllDepartments();
            $roles = Role::all();
            $permissions = Permission::all();

            // $staff = $this->userRepository->getUserById($staffId);
            $staff = User::find($staffId);
           

            // dd(count($staff->roles));
            // dd($staff->roles->pluck('id'));
            if($staff){
                 $staff_department = $staff->department;
                return view('systemadmins.editstaff', compact('staff','roles','permissions', 'departments', 'staff_department'));
            }else{
               return back()->with('error', 'Staff Not Found'); 
            }
        }
    }

    public function saveNewStaffInfo(Request $request){
        try{
            $permissions = Permission::whereIn('id', $request->permissions)->pluck('name');
            $roles = Role::whereIn('id', $request->roles)->pluck('name');
            
            // $staff =  $this->userRepository->getUserById($request->staffid);
            $staff = User::find($request->staffid);
            // dd($staff);
            $staff->syncPermissions($permissions);
            $staff->syncRoles($roles);

            return redirect()->to('/all-staffs')->with('success', 'Staff Information successfully updated');

            
        }catch(Exception $e){
            Log::error('STAFF_EDIT_ERROR =>'.$e->getMessage());
            return back()->with('error', 'Opps something went wrong.Pleasetry again later');
        }
    }
}