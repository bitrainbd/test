<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RoleController extends Controller
{
    public function index(Request $request): View
    {
        if (is_null(auth()->user()) || !auth()->user()->can('role.read')) {
            abort(403, 'Sorry !! You are Unauthorized to view any user !');
        }
        
        $roles = Role::get();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create(): View
    {
        if (is_null(auth()->user()) || !auth()->user()->can('role.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        $all_permissions  = Permission::all();
        $permission_groups = User::getPermissionGroups();
        return view('roles.create', compact('all_permissions', 'permission_groups'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        if (is_null(auth()->user()) || !auth()->user()->can('role.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any role !');
        }

        // dd($request->all());
        DB::transaction(function () use ($request) {
            $request->validate([
                'name' => 'required|max:100|unique:roles'
            ], [
                'name.requried' => 'Please give a role name'
            ]);

            $role = Role::create(['name' => $request->name]);

            $permissions = $request->input('permissions');

            if (!empty($permissions)) {
                $role->syncPermissions($permissions);
            }

        });

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }


    public function edit(Role $role): View
    {
        if (is_null(auth()->user()) || !auth()->user()->can('role.update')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        $all_permissions = Permission::all();
        $permission_groups = User::getPermissionGroups();

        return view('roles.edit', compact('role', 'all_permissions', 'permission_groups'));

    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, Role $role)
    {
        if (is_null(auth()->user()) || !auth()->user()->can('role.update')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any role !');
        }

        DB::transaction(function () use ($request, $role) {

        $request->validate([
                'name' => 'required|max:100|unique:roles,name,' . $role->id
            ], [
                'name.requried' => 'Please give a role name'
            ]
        );

        $permissions = $request->input('permissions');

        if (!empty($permissions)) {
                $role->name = $request->name;
                $role->save();
                $role->syncPermissions($permissions);
            }
   
        });

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Soft-delete the specified user (Admin only — enforced by Policy).
     */
    public function destroy(Role $role)
    {
        if (is_null(auth()->user()) || !auth()->user()->can('role.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any role !');
        }

        if (!is_null($role)) {
            $role->delete();
        }

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}