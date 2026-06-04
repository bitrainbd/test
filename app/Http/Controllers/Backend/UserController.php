<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Board;
use App\Models\Institution;
use App\Models\Klass;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;


class UserController extends Controller
{   

    public function index(Request $request): View
    {
        if (is_null(auth()->user()) || !auth()->user()->can('user.read')) {
            abort(403, 'Sorry !! You are Unauthorized to view any user !');
        }
        $query = User::with('profile')
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($q2) use ($search) {
                    $q2->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest();

        $users = $query->paginate(15)->withQueryString();

        // $users = User::paginate(10);
        return view('users.index', compact('users'));
    }


    public function create(): View
    {
        if (is_null(auth()->user()) || !auth()->user()->can('user.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any user !');
        }
        $klasses = Klass::get();
        $institutions = Institution::get();
        $boards = Board::get();
        $roles = Role::get();
        return view('users.create', compact('roles','klasses','institutions','boards'));
    }


    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        if (is_null(auth()->user()) || !auth()->user()->can('user.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any user !');
        }

        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'       => ['required', 'string', 'max:20'],
            'username'    => ['required', 'string', 'max:255', 'unique:users,username', 'alpha_dash'],
            'dob'  => ['nullable', 'date', 'before:today'],
            // 'password'    => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
            'roles'        => ['required'],
 
            // Profile table
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'username' => $request->username,
                'password' => 'password', // cast hashes it
                'status'   => $request->status,
                'dob'   => $request->dob,
                'address'   => $request->address,
            ]);           

            $avatarPath = null;
            if ($request->hasFile('avatar')) {
                $extension = $request->file('avatar')->getClientOriginalExtension();
                $filename = $user->id . '_' . Str::random(40) . '.' . $extension;
                $avatarPath = $request->file('avatar')
                    ->storeAs('avatars', $filename, 'public');
            }

            if(!is_null($avatarPath)){
                $user->avatar_url  =  $avatarPath;
                $user->save();
            }

            if ($request->roles) {
                $user->assignRole($request->roles);
            }

            if($request->is_student == 'on'){
                $user->profile()->create([
                    'board_id'  => $request->board_id,
                    'klass_id'    => $request->klass_id,
                    'institution_id' => $request->institution_id,
                    'group' => $request->group,
                ]);
            }
        });

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user): View
    {
        if (is_null(auth()->user()) || !auth()->user()->can('user.read')) {
            abort(403, 'Sorry !! You are Unauthorized to view any user !');
        }

        $user->load('profile');

        return view('users.show', compact('user'));
    }

    
    public function edit(User $user): View
    {
        if (is_null(auth()->user()) || !auth()->user()->can('user.update')) {
            abort(403, 'Sorry !! You are Unauthorized to update any user !');
        }

        $user = $user->load('profile');
        $klasses = Klass::get();
        $institutions = Institution::get();
        $boards = Board::get();
        $roles = Role::get();
        return view('users.edit', compact('user','roles','klasses','institutions','boards'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        //  dd($request->all());
        if (is_null(auth()->user()) || !auth()->user()->can('user.update')) {
            abort(403, 'Sorry !! You are Unauthorized to update any user !');
        }

        $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'email'       => ['required', 'email', 'max:255', "unique:users,email,{$user->id}"],
            'phone'       => ['required', 'string', 'max:20'],
            'username'    => ['required', 'string', 'max:255', "unique:users,username,{$user->id}", 'alpha_dash'],
            'dob'  => ['nullable', 'date', 'before:today'],
            'password'    => ['nullable','confirmed', Password::min(8)->mixedCase()->numbers()],
            'roles'        => ['required'],
 
            // Profile table
            'avatar'      => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);


        DB::transaction(function () use ($request, $user) {

            $userData = [
                'name'     => $request->name,
                'email'    => $request->email,
                'phone'    => $request->phone,
                'username' => $request->username,
                'status'   => $request->status,
                'dob'   => $request->dob,
                'address'   => $request->address,
            ];

            if ($request->filled('password')) {
                $userData['password'] = $request->password;
            }


            if ($request->hasFile('avatar')) {
                // Remove old avatar if exists
                if ($user->avatar_url) {
                    Storage::disk('public')->delete($user->avatar_url);
                }
                $extension = $request->file('avatar')->getClientOriginalExtension();
                $filename = $user->id . '_' . Str::random(40) . '.' . $extension;

                $userData['avatar_url'] = $request->file('avatar')
                    ->storeAs('avatars', $filename, 'public');
            }

            $user->update($userData);


            if ($request->roles) {
                $user->roles()->detach();
                $user->assignRole($request->roles);
            }

            if($request->is_student == 'on'){
                $user->profile()->updateOrCreate([],[
                    'board_id'  => $request->board_id,
                    'klass_id'    => $request->klass_id,
                    'institution_id' => $request->institution_id,
                    'group' => $request->group,
                ]);
            }else{
                $user->profile()->delete();
            }

        });

        return redirect()->route('users.index', $user)
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if (is_null(auth()->user()) || !auth()->user()->can('user.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any user !');
        }

        $user->delete();
        // $user->profile()->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}