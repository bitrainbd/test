@extends('master')

@section('title', 'Social Settings')

@section('header', 'Social Settings')

@section('body')
<div class="container-lg py-2">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            @include('settings.inc._tabs', ['active' => 'social'])

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('settings.social.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- ── Open Graph / Twitter ── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold text-secondary">
                            <i class="bi bi-share me-2"></i>Open Graph &amp; Twitter card
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        {{-- OG preview card --}}
                        <div class="mb-4">
                            <p class="form-label fw-medium mb-2">Preview</p>
                            <div class="border rounded overflow-hidden" style="max-width:480px">
                                <div id="og-preview-img" class="bg-light d-flex align-items-center justify-content-center text-muted small" style="height:120px">
                                    @if(!empty($og_image))                                    
                                        <img src="{{ $og_image }}" alt="OG image"
                                             class="w-100 h-100 object-fit-cover" style="object-fit:cover">
                                    @else
                                        <i class="bi bi-image fs-2 opacity-25"></i>
                                    @endif
                                </div>
                                <div class="p-3 bg-white">
                                    <div class="text-uppercase text-muted" style="font-size:11px">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</div>
                                    <div class="fw-semibold small" id="og-title-preview">{{ $settings['og_title'] ?? $settings['meta_title'] ?? config('app.name') }}</div>
                                    <div class="text-muted small text-truncate" id="og-desc-preview">{{ $settings['og_description'] ?? $settings['meta_description'] ?? '' }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- OG image --}}
                        <div class="mb-3">
                            <label class="form-label fw-medium">
                                OG image <span class="text-muted fw-normal">(1200×630 recommended)</span>
                            </label>
                            @if(!empty($settings['og_image']))
                                <div class="mb-2">
                                    <img src="{{ Storage::url($settings['og_image']) }}" alt="OG image"
                                         class="img-thumbnail" style="max-height:100px">
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="remove_og_image" value="1" id="remove_og_image">
                                        <label class="form-check-label text-muted small" for="remove_og_image">Remove image</label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('og_image') is-invalid @enderror"
                                   name="og_image" accept=".jpg,.jpeg,.png,.webp">
                            @error('og_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">JPG, PNG, WebP · max 4 MB · shown on Twitter, Facebook, LinkedIn shares</div>
                        </div>

                        {{-- OG title --}}
                        <div class="mb-3">
                            <label for="og_title" class="form-label fw-medium">OG title</label>
                            <input type="text" class="form-control @error('og_title') is-invalid @enderror"
                                   id="og_title" name="og_title" maxlength="95"
                                   value="{{ old('og_title', $settings['og_title'] ?? '') }}"
                                   oninput="document.getElementById('og-title-preview').textContent = this.value || '—'">
                            @error('og_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- OG description --}}
                        <div class="mb-3">
                            <label for="og_description" class="form-label fw-medium">OG description</label>
                            <textarea class="form-control @error('og_description') is-invalid @enderror"
                                      id="og_description" name="og_description" rows="3" maxlength="200"
                                      oninput="document.getElementById('og-desc-preview').textContent = this.value">{{ old('og_description', $settings['og_description'] ?? '') }}</textarea>
                            @error('og_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        {{-- OG type + Twitter card + handle --}}
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">OG type</label>
                                <select class="form-select" name="og_type">
                                    @foreach(['website', 'article', 'product', 'profile'] as $t)
                                        <option @selected(old('og_type', $settings['og_type'] ?? 'website') === $t)>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Twitter card</label>
                                <select class="form-select" name="twitter_card">
                                    @foreach(['summary_large_image', 'summary', 'app', 'player'] as $t)
                                        <option @selected(old('twitter_card', $settings['twitter_card'] ?? 'summary_large_image') === $t)>{{ $t }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Twitter handle</label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text" class="form-control" name="twitter_handle"
                                           placeholder="yourhandle"
                                           value="{{ ltrim(old('twitter_handle', $settings['twitter_handle'] ?? ''), '@') }}">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- ── Social Links ── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold text-secondary">
                            <i class="bi bi-link-45deg me-2"></i>Social links
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            @foreach([
                                'facebook'  => ['bi-facebook',  'Facebook',    '#1877F2'],
                                'twitter'   => ['bi-twitter-x', 'Twitter / X', '#000000'],
                                'instagram' => ['bi-instagram', 'Instagram',   '#E1306C'],
                                'linkedin'  => ['bi-linkedin',  'LinkedIn',    '#0A66C2'],
                                'youtube'   => ['bi-youtube',   'YouTube',     '#FF0000'],
                                'github'    => ['bi-github',    'GitHub',      '#333333'],
                            ] as $platform => [$icon, $label, $color])
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">
                                        <i class="bi {{ $icon }} me-1" style="color:{{ $color }}"></i>{{ $label }}
                                    </label>
                                    <input type="url" class="form-control font-monospace @error("social_links.$platform") is-invalid @enderror"
                                           name="social_links[{{ $platform }}]"
                                           value="{{ old("social_links.$platform", $social_links[$platform] ?? '') }}"
                                           placeholder="https://">
                                    @error("social_links.$platform")
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark px-4">
                        <i class="bi bi-floppy me-1"></i> Save social settings
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection