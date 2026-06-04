@extends('master')

@section('title', 'All Users | OTP')


@section('header')
    All Users
@endsection


@section('body')
    <div class="d-flex justify-content-end align-items-center mb-2">
        @can('user.create')
            <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Add User
            </a>
        @endcan
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            {{-- Search & Filter --}}
            <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small mb-1">Search</label>
                    <input type="text"
                           name="search"
                           class="form-control form-control-sm"
                           placeholder="Name, email, username, phone…"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Status</label>
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        @foreach (['active','inactive','suspended','deactivated'] as $s)
                            <option value="{{ $s }}" @selected(request('status') === $s)>
                                {{ ucfirst($s) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-secondary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary ms-1">
                        <i class="bi bi-x-lg"></i> Clear
                    </a>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width:50px">#</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $loop->iteration + ($users->firstItem() - 1) }}</td>
                                <td>
                                    @if ($user->profile?->avatar_url)
                                        <img src="{{ Storage::url($user->profile->avatar_url) }}"
                                             alt="{{ $user->name }}"
                                             class="rounded-circle"
                                             width="38" height="38"
                                             style="object-fit:cover;">
                                    @else
                                        <span class="avatar-placeholder rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white"
                                              style="width:38px;height:38px;font-size:.85rem;font-weight:600;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $user->name }}</td>
                                <td><code>{{ $user->username }}</code></td>
                                <td>
                                    @if ($user->roles->count() > 0)
                                        @foreach ($user->roles as $role)
                                            @if ($role->name == 'Admin')
                                                <span class="badge bg-danger">{{ $role->name }}</span>   
                                            @elseif($role->name == 'Editor')
                                                <span class="badge bg-primary">{{ $role->name }}</span>                                                
                                            @else
                                                <span class="badge bg-success">{{ $role->name }}</span>                                                
                                            @endif
                                        @endforeach
                                    @else
                                        <span class="badge bg-secondary">No Role</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    @php
                                        $badge = match($user->status) {
                                            'ACTIVE'      => 'success',
                                            'INACTIVE'    => 'secondary',
                                            'SUSPENDED'   => 'warning',
                                            'DEACTIVATED' => 'danger',
                                            default       => 'secondary',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">{{ ucfirst($user->status) }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                        @can('user.read')
                                            <a href="{{ route('users.show', $user) }}"
                                               class="btn btn-outline-success me-1" title="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        @endcan
                                        @can('user.update')
                                            <a href="{{ route('users.edit', $user) }}"
                                               class="btn btn-outline-primary me-1" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('user.delete')
                                            <button type="button"
                                                    class="btn btn-outline-danger"
                                                    title="Delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted py-4">
                                    <i class="bi bi-people fs-2 d-block mb-2"></i>
                                    No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @can('user.delete')  {{-- renders modal markup only if admin --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteModalLabel">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Confirm Delete
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="deleteUserName"></strong>?
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('js')
<script>
    const deleteModal = document.getElementById('deleteModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const btn      = event.relatedTarget;
            const userId   = btn.dataset.userId;
            const userName = btn.dataset.userName;

            document.getElementById('deleteUserName').textContent = userName;
            document.getElementById('deleteForm').action =
                `{{ url('users') }}/${userId}`;
        });
    }
</script>
@endpush