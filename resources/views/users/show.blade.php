@extends('master')

@section('title', 'User – ' . $user->name)


@section('header')
    View User
@endsection

@section('body')

    <div class="d-flex justify-content-end align-items-center mb-2">
        {{-- <h1 class="m-0">{{ $user->name }}</h1> --}}
        <div class="d-flex gap-2">
            @can('user.update', $user)
                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-pencil me-1"></i> Edit
                </a>
            @endcan
            <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back
            </a>
        </div>
    </div>

    <div class="row">
        {{-- ── Profile Card ── --}}
        <div class="col-lg-4">
            <div class="card card-outline card-primary text-center">
                <div class="card-body pt-4">
                    @if ($user->profile?->avatar_url)
                        <img src="{{ Storage::url($user->profile->avatar_url) }}"
                             alt="{{ $user->name }}"
                             class="rounded-circle img-fluid mb-3"
                             style="width:120px;height:120px;object-fit:cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-3"
                             style="width:120px;height:120px;font-size:2.5rem;font-weight:700;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-2"><code>{{ '@'.$user->username }}</code></p>

                    @php
                        $badge = match($user->status) {
                            'active'      => 'success',
                            'inactive'    => 'secondary',
                            'suspended'   => 'warning',
                            'deactivated' => 'danger',
                            default       => 'secondary',
                        };
                    @endphp
                    <span class="badge bg-{{ $badge }} fs-6 px-3 py-2">
                        {{ ucfirst($user->status) }}
                    </span>
                </div>
                <div class="card-footer text-muted small">
                    Joined {{ $user->created_at->format('d M Y') }}
                </div>
            </div>

            {{-- Quick Info --}}
            <div class="card card-outline card-light">
                <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-info-circle me-1"></i> Quick Info</h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-envelope me-1"></i> Email</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-telephone me-1"></i> Phone</span>
                            <span>{{ $user->phone }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted"><i class="bi bi-shield-check me-1"></i> Email Verified</span>
                            <span>
                                @if ($user->email_verified_at)
                                    <span class="text-success"><i class="bi bi-check-circle-fill"></i> Yes</span>
                                @else
                                    <span class="text-warning"><i class="bi bi-x-circle-fill"></i> No</span>
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- ── Profile Details ── --}}
        <div class="col-lg-8">
            <div class="card card-outline card-info">
                <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-mortarboard-fill me-1"></i> Academic Profile</h3>
                </div>
                <div class="card-body">
                    @if ($user->profile)
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <p class="text-muted small mb-1">Class Level</p>
                                <p class="fw-semibold mb-0">
                                    {{ $user->profile->class_level ? strtoupper($user->profile->class_level) : '—' }}
                                </p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted small mb-1">Institution</p>
                                <p class="fw-semibold mb-0">{{ $user->profile->institution ?: '—' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted small mb-1">Board Name</p>
                                <p class="fw-semibold mb-0">{{ $user->profile->board_name ?: '—' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <p class="text-muted small mb-1">Date of Birth</p>
                                <p class="fw-semibold mb-0">
                                    {{ optional($user->profile->birth_date)->format('d M Y') ?? '—' }}
                                </p>
                            </div>
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">
                            <i class="bi bi-info-circle me-1"></i> No profile information added yet.
                        </p>
                    @endif
                </div>
            </div>

            {{-- Timestamps --}}
            <div class="card card-outline card-light">
                <div class="card-header">
                    <h3 class="card-title"><i class="bi bi-clock-history me-1"></i> Timestamps</h3>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Created At</p>
                            <p class="fw-semibold mb-0">{{ $user->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-sm-4">
                            <p class="text-muted small mb-1">Updated At</p>
                            <p class="fw-semibold mb-0">{{ $user->updated_at->format('d M Y, h:i A') }}</p>
                        </div>
                        @if ($user->deleted_at)
                            <div class="col-sm-4">
                                <p class="text-muted small mb-1">Deleted At</p>
                                <p class="fw-semibold mb-0 text-danger">{{ $user->deleted_at->format('d M Y, h:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            @canany(['user.update', 'user.delete'], $user)
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title"><i class="bi bi-gear me-1"></i> Actions</h3>
                    </div>
                    <div class="card-body d-flex gap-2 flex-wrap">
                        @can('user.update', $user)
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i> Edit User
                            </a>
                        @endcan

                        @can('user.delete', $user)
                            <button type="button"
                                    class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteModal">
                                <i class="bi bi-trash me-1"></i> Delete User
                            </button>
                        @endcan
                    </div>
                </div>
            @endcanany
        </div>
    </div>

    {{-- Delete Modal --}}
    @can('user.delete', $user)
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> Confirm Delete
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Delete <strong>{{ $user->name }}</strong>? This is a soft delete and can be recovered.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
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