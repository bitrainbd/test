@extends('master')

@section('title', 'Create Role')

@push('css')
<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endpush

@section('header')
    <div style="width: 50%" class="text-center">
        Add Role
    </div>
@endsection

@section('body')

<div class="card card-outline card-primary col-6" >
    <div class="card-body">
        <form action="{{ route('roles.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-12">
                <label for="name" class="form-label">Role Name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" placeholder="Eg: Admin or Manager"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-12">
                <label for="name" class="form-label">Permissions <span class="text-danger">*</span></label>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="checkPermissionAll" value="1">
                    <label class="form-check-label" for="checkPermissionAll">All</label>
                </div>
                <hr>
                @php $i = 1; @endphp
                @foreach ($permission_groups as $group)
                    <div class="row">
                        <div class="col-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="{{ $i }}Management" value="{{ $group->name }}" onclick="checkPermissionByGroup('role-{{ $i }}-management-checkbox', this)">
                                <label class="form-check-label" for="{{ $i }}Management">{{ $group->name }}</label>
                            </div>
                        </div>

                        <div class="col-9 role-{{ $i }}-management-checkbox">
                            @php
                                $permissions = App\Models\User::getPermissionsByGroupName($group->name);
                                $j = 1;
                            @endphp
                            @foreach ($permissions as $permission)
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" onclick="checkSinglePermission('role-{{ $i }}-management-checkbox', '{{ $i }}Management', {{ count($permissions) }})" name="permissions[]" id="checkPermission{{ $permission->id }}" value="{{ $permission->name }}">
                                    <label class="form-check-label" for="checkPermission{{ $permission->id }}">{{ $permission->name }}</label>
                                </div>
                                @php  $j++; @endphp
                            @endforeach
                            <br>
                        </div>

                    </div>
                    @php  $i++; @endphp
                @endforeach
            </div>
            <div class="col-md-12">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Create Role
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>

        </div>
        </form>
    </div>
</div>

@endsection

@push('js')
    @include('roles.partials.scripts')
@endpush