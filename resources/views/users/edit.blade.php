@extends('master')

@section('title', 'Edit User | BitRain')

@section('header')
    Edit User
@endsection

@push('css')

@endpush

@section('body')

    <div class="d-flex justify-content-end align-items-center">
        {{-- <h1 class="m-0">Add New User</h1> --}}
        <a href="{{ route('users.index', $user) }}" class="btn btn-sm btn-outline-secondary mb-2">
            <i class="bi bi-arrow-left me-1"></i> Back to Users
        </a>
    </div>

    <form action="{{ route('users.update',$user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            {{-- ── Account Information ── --}}
            <div class="col-lg-8">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-person-fill me-1"></i> Account Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" id="name" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name',$user->name) }}" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" id="username" name="username"
                                           class="form-control @error('username') is-invalid @enderror"
                                           value="{{ old('username', $user->username) }}" required>
                                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                <input type="text" id="phone" name="phone"
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone',$user->phone) }}" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                             <div class="col-md-6">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" id="address" name="address"
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address', $user->address) }}">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" id="dob" name="dob"
                                       class="form-control @error('dob') is-invalid @enderror"
                                       value="{{ old('dob', $user->dob) }}">
                                @error('dob')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" id="password" name="password"
                                           class="form-control @error('password') is-invalid @enderror">
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="bi bi-eye" id="eyeIcon"></i>
                                    </button>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select id="status" name="status"
                                        class="form-select @error('status') is-invalid @enderror" required>
                                    @foreach (['ACTIVE','INACTIVE','SUSPENDED','DEACTIVATED'] as $s)
                                        <option value="{{ $s }}" @selected(old('status', $user->status) === $s)>
                                            {{ ucfirst($s) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="roles" class="form-label">Role <span class="text-danger">*</span></label>
                                <select id="roles" name="roles[]" class="form-select @error('roles') is-invalid @enderror" required>
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Profile Information ── --}}
                <div class="card card-outline card-info mt-2">
                    <div class="card-header d-flex justify-content-between">
                        <div>
                            <h3 class="card-title"><i class="bi bi-mortarboard-fill me-1"></i> Profile Information</h3> &nbsp; <span class="badge bg-secondary">Optional</span>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="showBox" name="is_student" {{is_null($user->profile) ? '' : 'checked'}}>
                            <label class="form-check-label" for="showBox" style="cursor:pointer;">
                                is Student?
                            </label>
                        </div>            
                    </div>
                    <div class="card-body" id="content" style="display: {{is_null($user->profile) ? 'none' : 'block'}}">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="board_id" class="form-label">Board Name</label>
                                <select id="board_id" name="board_id"
                                        class="form-select @error('board_id') is-invalid @enderror">
                                    <option value="">— Select —</option>
                                    @foreach ($boards as $board)
                                        <option value="{{ $board->id }}"  @selected(old('board_id', $user->profile?->board_id) === $board->id)>{{ $board->name }}</option>
                                    @endforeach
                                </select>
                                @error('board_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="institution_id" class="form-label">Institution</label>                                
                                <select id="institution_id" name="institution_id" style="width: 100%"
                                        class="form-select @error('institution_id') is-invalid @enderror">
                                    <option value="">— Select —</option>
                                    @foreach ($institutions as $institution)
                                        <option value="{{ $institution->id }}" @selected(old('institution_id', $user->profile?->institution_id) === $institution->id)>{{ $institution->name }} ({{$institution->eiin}})</option>
                                    @endforeach
                                </select>
                                @error('institution_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="klass_id" class="form-label">Class Level</label>
                                <select id="klass_id" name="klass_id"
                                        class="form-select @error('klass_id') is-invalid @enderror">
                                    <option value="">— Select —</option>
                                    @foreach ($klasses as $klass)
                                        <option value="{{ $klass->id }}" @selected(old('klass_id', $user->profile?->klass_id) === $klass->id)>{{ $klass->name }}</option>
                                    @endforeach
                                </select>
                                @error('klass_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label for="group" class="form-label">Group</label>
                                <select id="group" name="group"
                                        class="form-select @error('group') is-invalid @enderror">
                                    <option value="">— Select —</option>
                                    @foreach (['SCIENCE','BUSINESS STUDIES','HUMANITIES'] as $group)
                                        <option value="{{ $group }}" @selected(old('group', $user->profile?->group) === $group)>{{ $group }}</option>
                                    @endforeach
                                </select>
                                @error('group')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>                    

          
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Avatar ── --}}
            <div class="col-lg-4">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-image me-1"></i> Avatar</h3>
                    </div>
                    <div class="card-body text-center">
                        <img id="avatarPreview"
                             src="{{ asset('storage/' . $user->avatar_url)}}"
                             alt="Avatar Preview"
                             class="rounded-circle img-fluid mb-3"
                             style="width:130px;height:130px;object-fit:cover;">

                        <div>
                            <label for="avatar" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-upload me-1"></i> Upload Photo
                            </label>
                            <input type="file" id="avatar" name="avatar"
                                   class="d-none @error('avatar') is-invalid @enderror"
                                   accept="image/jpg,image/jpeg,image/png,image/webp">
                            @error('avatar')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                            <p class="text-muted small mt-2 mb-0">JPG, PNG or WEBP · Max 2 MB</p>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-1"></i> Update User
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('js')
<script>
    $('#showBox').change(function () {

        if($(this).is(':checked')) {
            $('#content').show();
        } else {
            $('#content').hide();
        }
    });

    $('#institution_id').select2({
        placeholder: "Search institution",
        allowClear: true
    });


    // Avatar preview
    document.getElementById('avatar').addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('avatarPreview').src = e.target.result;
            reader.readAsDataURL(file);
        }
    });

    // Password toggle
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>
@endpush