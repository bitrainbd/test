@extends('master')

@section('title', 'General Settings')

@section('header', 'General Settings')

@section('body')
<div class="container-lg py-2">
    <div class="row justify-content-center">
        <div class="col-xl-8">

            @include('settings.inc._tabs', ['active' => 'general'])

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('settings.general.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── Site Identity ── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold text-secondary">
                            <i class="bi bi-globe2 me-2"></i>Site identity
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Favicon --}}
                        <div class="mb-4">
                            <label class="form-label fw-medium">Favicon</label>
                            @if(!empty($appearance['favicon']))
                                <div class="d-flex align-items-center gap-3 mb-2">
                                    <img src="{{ Storage::url($appearance['favicon']) }}" alt="favicon"
                                         class="border rounded" style="width:32px;height:32px;object-fit:contain">
                                    <div class="form-check mb-0">
                                        <input class="form-check-input" type="checkbox" name="remove_favicon" value="1" id="remove_favicon">
                                        <label class="form-check-label text-muted small" for="remove_favicon">Remove favicon</label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('favicon') is-invalid @enderror"
                                   name="favicon" accept=".ico,.png,.svg,.jpg,.webp">
                            @error('favicon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">ICO, PNG, SVG · max 512 KB · 32×32 recommended</div>
                        </div>

                        {{-- Logo --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Logo <span class="text-muted fw-normal">(light)</span></label>
                                @if(!empty($appearance['logo']))
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <img src="{{ Storage::url($appearance['logo']) }}" alt="logo"
                                             class="border rounded p-1" style="height:36px;object-fit:contain">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="remove_logo" value="1" id="remove_logo">
                                            <label class="form-check-label text-muted small" for="remove_logo">Remove</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('logo') is-invalid @enderror"
                                       name="logo" accept=".png,.svg,.jpg,.webp">
                                @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Logo <span class="text-muted fw-normal">(dark)</span></label>
                                @if(!empty($appearance['logo_dark']))
                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        <img src="{{ Storage::url($appearance['logo_dark']) }}" alt="logo dark"
                                             class="border rounded p-1 bg-dark" style="height:36px;object-fit:contain">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox" name="remove_logo_dark" value="1" id="remove_logo_dark">
                                            <label class="form-check-label text-muted small" for="remove_logo_dark">Remove</label>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('logo_dark') is-invalid @enderror"
                                       name="logo_dark" accept=".png,.svg,.jpg,.webp">
                                @error('logo_dark') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Site title --}}
                        <div class="mb-3">
                            <label for="site_title" class="form-label fw-medium">Site title</label>
                            <input type="text" class="form-control @error('site_title') is-invalid @enderror"
                                   id="site_title" name="site_title" maxlength="100"
                                   value="{{ old('site_title', $settings['site_title'] ?? '') }}">
                            @error('site_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- Site description --}}
                        <div class="mb-3">
                            <label for="site_description" class="form-label fw-medium">Site description</label>
                            <textarea class="form-control @error('site_description') is-invalid @enderror"
                                      id="site_description" name="site_description" rows="3"
                                      maxlength="500">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                            @error('site_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- URL + Language --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label for="site_url" class="form-label fw-medium">Site URL</label>
                                <input type="url" class="form-control @error('site_url') is-invalid @enderror"
                                       id="site_url" name="site_url"
                                       value="{{ old('site_url', $settings['site_url'] ?? '') }}"
                                       placeholder="https://yoursite.com">
                                @error('site_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-5">
                                <label for="site_language" class="form-label fw-medium">Language</label>
                                <select class="form-select" id="site_language" name="site_language">
                                    @foreach(['en' => 'English', 'bn' => 'Bengali', 'fr' => 'French', 'de' => 'German', 'es' => 'Spanish', 'ar' => 'Arabic', 'zh' => 'Chinese', 'ja' => 'Japanese'] as $code => $label)
                                        <option value="{{ $code }}" @selected(old('site_language', $settings['site_language'] ?? 'en') === $code)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Theme color --}}
                        <div class="mb-1">
                            <label for="theme_color" class="form-label fw-medium">Theme color</label>
                            <div class="input-group" style="max-width:260px">
                                <input type="color" class="form-control form-control-color"
                                       id="theme_color_picker"
                                       value="{{ old('theme_color', $settings['theme_color'] ?? '#1a1a1a') }}"
                                       title="Choose theme color"
                                       oninput="document.getElementById('theme_color').value=this.value">
                                <input type="text" class="form-control font-monospace @error('theme_color') is-invalid @enderror"
                                       id="theme_color" name="theme_color" maxlength="7" placeholder="#1a1a1a"
                                       value="{{ old('theme_color', $settings['theme_color'] ?? '#1a1a1a') }}"
                                       oninput="syncColor(this)">
                                @error('theme_color') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="form-text">Used by mobile browsers to color the browser toolbar.</div>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark px-4">
                        <i class="bi bi-floppy me-1"></i> Save general settings
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function syncColor(input) {
    const val = input.value;
    if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
        document.getElementById('theme_color_picker').value = val;
    }
}
</script>
@endsection