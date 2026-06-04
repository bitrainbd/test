@extends('master')

@section('title', 'All Users | OTP')


@section('header')
    All Roles
@endsection


@section('body')
    <div class="d-flex justify-content-end align-items-center mb-2">
        @can('role.create')
            <a href="{{ route('roles.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus-fill me-1"></i> Add Role
            </a>
        @endcan
    </div>

    <div class="card card-outline card-primary">

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">Sl</th>
                            <th width="10%">Name</th>
                            <th width="60%">Permissions</th>
                            <th width="15%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $role->name }}</td>
                                <td>
                                    @foreach ($role->permissions as $perm)
                                        <span class="badge bg-success mr-1">
                                            {{ $perm->name }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">                                       
                                        @can('role.update')
                                            <a href="{{ route('roles.edit', $role) }}"
                                               class="btn btn-outline-primary me-1" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('role.delete')
                                            <button type="button"
                                                    class="btn btn-outline-danger"
                                                    title="Delete"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal"
                                                    data-role-id="{{ $role->id }}"
                                                    data-role-name="{{ $role->name }}">
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
                                    No role found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @can('role.delete')  {{-- renders modal markup only if admin --}}
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
                        Are you sure you want to delete <strong id="deleteRoleName"></strong>?
                        
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
            const roleId   = btn.dataset.roleId;
            const roleName = btn.dataset.roleName;

            document.getElementById('deleteRoleName').textContent = roleName;
            document.getElementById('deleteForm').action =
                `{{ url('roles') }}/${roleId}`;
        });
    }
</script>
@endpush