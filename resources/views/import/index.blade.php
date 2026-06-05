<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Import</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f4f6f9;
            color: #2c3e50;
            min-height: 100vh;
        }

        header {
            background: #2D6A4F;
            color: white;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,.2);
        }
        header h1 { font-size: 1.4rem; }

        .container { max-width: 1100px; margin: 2rem auto; padding: 0 1.5rem; }

        /* ── Alert ── */
        .alert {
            padding: .9rem 1.2rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: .95rem;
        }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid #10b981; }
        .alert-error   { background: #fee2e2; color: #991b1b; border-left: 4px solid #ef4444; }

        /* ── Import card ── */
        .card {
            background: white;
            border-radius: 12px;
            padding: 1.8rem;
            box-shadow: 0 1px 6px rgba(0,0,0,.08);
            margin-bottom: 2rem;
        }
        .card h2 { font-size: 1.1rem; margin-bottom: 1.2rem; color: #2D6A4F; }

        .upload-area {
            border: 2px dashed #a8d5be;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: background .2s;
            position: relative;
        }
        .upload-area:hover { background: #f0faf4; }
        .upload-area input[type=file] {
            position: absolute; inset: 0; opacity: 0; cursor: pointer;
        }
        .upload-area svg { color: #2D6A4F; margin-bottom: .5rem; }
        .upload-area p   { color: #6b7280; font-size: .9rem; }
        .upload-area strong { display: block; color: #2D6A4F; margin-bottom: .3rem; }

        .file-label {
            display: inline-block;
            margin-top: .8rem;
            font-size: .85rem;
            color: #374151;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .6rem 1.4rem;
            border-radius: 7px;
            border: none;
            font-size: .9rem;
            cursor: pointer;
            font-weight: 500;
            transition: opacity .15s;
            text-decoration: none;
        }
        .btn:hover { opacity: .88; }
        .btn-primary { background: #2D6A4F; color: white; }
        .btn-danger  { background: #ef4444; color: white; font-size: .82rem; padding: .45rem 1rem; }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-top: 1.2rem;
        }

        /* ── Table ── */
        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 6px rgba(0,0,0,.08);
            overflow: hidden;
        }
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        .table-header h2 { font-size: 1.05rem; color: #2D6A4F; }

        table { width: 100%; border-collapse: collapse; }
        th {
            background: #f9fafb;
            text-align: left;
            padding: .75rem 1rem;
            font-size: .8rem;
            text-transform: uppercase;
            letter-spacing: .05em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: .75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            font-size: .9rem;
            vertical-align: middle;
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #f9fafb; }

        .avatar {
            width: 44px; height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #a8d5be;
        }

        .badge {
            display: inline-block;
            background: #d1fae5;
            color: #065f46;
            font-size: .75rem;
            padding: .2rem .6rem;
            border-radius: 20px;
            font-weight: 500;
        }

        .empty {
            text-align: center;
            padding: 3rem;
            color: #9ca3af;
        }
        .empty p { margin-top: .5rem; font-size: .9rem; }

        /* Pagination */
        .pagination { padding: 1rem 1.5rem; display: flex; gap: .4rem; }
        .pagination a, .pagination span {
            padding: .4rem .75rem;
            border-radius: 6px;
            font-size: .85rem;
            border: 1px solid #e5e7eb;
            color: #374151;
            text-decoration: none;
        }
        .pagination .active span { background: #2D6A4F; color: white; border-color: #2D6A4F; }
    </style>
</head>
<body>

<header>
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>
    </svg>
    <h1>User Excel Importer</h1>
</header>

<div class="container">

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success">✓ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $e) {{ $e }}<br> @endforeach
        </div>
    @endif

    {{-- Import Form --}}
    <div class="card">
        <h2>📥 Import Users from Excel</h2>
        <form action="{{ route('import.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="upload-area" id="dropZone">
                <input type="file" name="file" id="fileInput" accept=".xlsx,.xls"
                       onchange="document.getElementById('fileName').textContent = this.files[0]?.name ?? 'No file chosen'">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <strong>Click or drag your Excel file here</strong>
                <p>Supports .xlsx and .xls — max 10 MB</p>
                <span class="file-label" id="fileName">No file chosen</span>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/>
                        <path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/>
                    </svg>
                    Import Users
                </button>
                <span style="color:#9ca3af;font-size:.85rem">
                    Expected columns: ID · Name · Email · Phone · Department · Photo
                </span>
            </div>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="table-card">
        <div class="table-header">
            <h2>👥 Users ({{ $users->total() }})</h2>
            @if($users->total() > 0)
            <form action="{{ route('import.clear') }}" method="POST"
                  onsubmit="return confirm('Delete ALL users?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger">🗑 Clear All</button>
            </form>
            @endif
        </div>

        @if($users->isEmpty())
        <div class="empty">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#d1d5db" stroke-width="1.5">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
            </svg>
            <p>No users yet. Import an Excel file to get started.</p>
        </div>
        @else
        <table>
            <thead>
                <tr>
                    <th>Photo</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Department</th>
                    <th>Imported</th>
                </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
                <tr>
                    <td>
                        <img class="avatar"
                             src="{{ $user->photoUrl() }}"
                             alt="{{ $user->name }}">
                    </td>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone ?? '—' }}</td>
                    <td><span class="badge">{{ $user->department ?? '—' }}</span></td>
                    <td style="color:#9ca3af;font-size:.82rem">{{ $user->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</div>

<script>
// Highlight drop zone on drag-over
const zone = document.getElementById('dropZone');
zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.background = '#e6f7ef'; });
zone.addEventListener('dragleave', ()  => { zone.style.background = ''; });
zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.style.background = '';
    const file = e.dataTransfer.files[0];
    if (file) {
        document.getElementById('fileInput').files = e.dataTransfer.files;
        document.getElementById('fileName').textContent = file.name;
    }
});
</script>
</body>
</html>
