@extends('master')

@section('title', 'SEO Settings')

@section('header', 'SEO Settings')

@section('body')
<div class="container-lg py-2">
    <div class="row justify-content-center">
        <div class="col-xl-8">

            @include('settings.inc._tabs', ['active' => 'seo'])

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('settings.seo.update') }}" method="POST">
                @csrf
                @method('PUT')

                {{-- ── Meta tags ── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold text-secondary">
                            <i class="bi bi-tags me-2"></i>Meta tags
                        </h6>
                    </div>
                    <div class="card-body p-4">

                        {{-- Meta title --}}
                        <div class="mb-4">
                            <label for="meta_title" class="form-label fw-medium">
                                Meta title
                                <span id="meta_title_len" class="ms-2 badge bg-secondary fw-normal">0 / 70</span>
                            </label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                   id="meta_title" name="meta_title" maxlength="70"
                                   value="{{ old('meta_title', $settings['meta_title'] ?? '') }}"
                                   oninput="countChars('meta_title', 70, 50, 60)">
                            @error('meta_title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Ideal: 50–60 characters for best search snippet display.</div>
                            <div class="progress mt-1" style="height:3px">
                                <div class="progress-bar bg-success" id="meta_title_bar" style="width:0%"></div>
                            </div>
                        </div>

                        {{-- Meta description --}}
                        <div class="mb-4">
                            <label for="meta_description" class="form-label fw-medium">
                                Meta description
                                <span id="meta_description_len" class="ms-2 badge bg-secondary fw-normal">0 / 160</span>
                            </label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                      id="meta_description" name="meta_description" rows="3" maxlength="160"
                                      oninput="countChars('meta_description', 160, 120, 160)">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
                            @error('meta_description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Ideal: 120–160 characters.</div>
                            <div class="progress mt-1" style="height:3px">
                                <div class="progress-bar bg-success" id="meta_description_bar" style="width:0%"></div>
                            </div>
                        </div>

                        {{-- Keywords --}}
                        <div class="mb-4">
                            <label class="form-label fw-medium">Keywords</label>
                            <div id="kw-wrap"
                                 class="form-control d-flex flex-wrap gap-2 align-items-center"
                                 style="min-height:46px;cursor:text;height:auto"
                                 onclick="document.getElementById('kw-input').focus()">
                                @foreach(old('meta_keywords', $settings['meta_keywords'] ?? []) as $kw)
                                    <span class="kw-tag badge rounded-pill bg-secondary d-inline-flex align-items-center gap-1 fw-normal fs-6 px-2">
                                        {{ $kw }}
                                        <input type="hidden" name="meta_keywords[]" value="{{ $kw }}">
                                        <button type="button" class="btn-close btn-close-white ms-1"
                                                style="font-size:.55rem" onclick="removeKw(this)" aria-label="Remove keyword"></button>
                                    </span>
                                @endforeach
                                <input id="kw-input" type="text" placeholder="Add keyword, press Enter…"
                                       class="border-0 bg-transparent flex-fill"
                                       style="min-width:160px;outline:none"
                                       onkeydown="addKw(event)">
                            </div>
                            <div class="form-text">Press Enter or comma to add a keyword.</div>
                        </div>

                        {{-- Canonical + Author --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-7">
                                <label for="canonical_url" class="form-label fw-medium">Canonical URL</label>
                                <input type="url" class="form-control @error('canonical_url') is-invalid @enderror"
                                       id="canonical_url" name="canonical_url"
                                       value="{{ old('canonical_url', $settings['canonical_url'] ?? '') }}"
                                       placeholder="https://yoursite.com/page">
                                @error('canonical_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-5">
                                <label for="meta_author" class="form-label fw-medium">Author</label>
                                <input type="text" class="form-control"
                                       id="meta_author" name="meta_author"
                                       value="{{ old('meta_author', $settings['meta_author'] ?? '') }}"
                                       placeholder="Full name or org">
                            </div>
                        </div>

                        {{-- Robots --}}
                        <div class="mb-0">
                            <label for="robots" class="form-label fw-medium">Robots directive</label>
                            <select class="form-select" id="robots" name="robots">
                                @foreach([
                                    'index,follow'    => 'Index & follow — Search engines crawl & follow links',
                                    'noindex,follow'  => 'No index, follow — Exclude from results, follow links',
                                    'noindex,nofollow'=> 'No index, no follow — Block crawling entirely',
                                    'index,nofollow'  => 'Index, no follow — Index page, don\'t follow links',
                                ] as $val => $label)
                                    <option value="{{ $val }}" @selected(old('robots', $settings['robots'] ?? 'index,follow') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                {{-- ── Verification ── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold text-secondary">
                            <i class="bi bi-patch-check me-2"></i>Site verification
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Google Search Console</label>
                                <input type="text" class="form-control font-monospace" name="google_verify"
                                       value="{{ old('google_verify', $settings['google_verify'] ?? '') }}"
                                       placeholder="Verification meta content value">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Bing Webmaster Tools</label>
                                <input type="text" class="form-control font-monospace" name="bing_verify"
                                       value="{{ old('bing_verify', $settings['bing_verify'] ?? '') }}"
                                       placeholder="Verification meta content value">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Toggles ── --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom">
                        <h6 class="mb-0 fw-semibold text-secondary">
                            <i class="bi bi-toggles me-2"></i>Options
                        </h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach([
                            'enable_sitemap' => ['Auto-generate sitemap.xml',     'Serve /sitemap.xml automatically for search crawlers'],
                            'enable_jsonld'  => ['JSON-LD structured data',        'Inject schema.org markup for Google rich snippets'],
                        ] as $key => [$label, $hint])
                            <li class="list-group-item px-4 py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-medium">{{ $label }}</div>
                                        <small class="text-muted">{{ $hint }}</small>
                                    </div>
                                    <div class="form-check form-switch mb-0 ms-3">
                                        <input type="hidden" name="{{ $key }}" value="0">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                               name="{{ $key }}" value="1" id="toggle_{{ $key }}"
                                               @checked(old($key, $settings[$key] ?? false))>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-dark px-4">
                        <i class="bi bi-floppy me-1"></i> Save SEO settings
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function countChars(id, max, idealMin, idealMax) {
    const el  = document.getElementById(id);
    const len = el.value.length;
    const badge = document.getElementById(id + '_len');
    const bar   = document.getElementById(id + '_bar');

    badge.textContent = len + ' / ' + max;

    const pct = Math.min(100, Math.round(len / max * 100));
    bar.style.width = pct + '%';

    if (len < idealMin) {
        bar.className = 'progress-bar bg-warning';
        badge.className = 'ms-2 badge bg-warning text-dark fw-normal';
    } else if (len <= idealMax) {
        bar.className = 'progress-bar bg-success';
        badge.className = 'ms-2 badge bg-success fw-normal';
    } else {
        bar.className = 'progress-bar bg-danger';
        badge.className = 'ms-2 badge bg-danger fw-normal';
    }
}

// init on load
['meta_title', 'meta_description'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.dispatchEvent(new Event('input'));
});

function addKw(e) {
    if (e.key !== 'Enter' && e.key !== ',') return;
    e.preventDefault();
    const val = e.target.value.replace(',', '').trim();
    if (!val) return;
    const wrap = document.getElementById('kw-wrap');
    const tag  = document.createElement('span');
    tag.className = 'kw-tag badge rounded-pill bg-secondary d-inline-flex align-items-center gap-1 fw-normal fs-6 px-2';
    tag.innerHTML = `${val}<input type="hidden" name="meta_keywords[]" value="${val}">
        <button type="button" class="btn-close btn-close-white ms-1" style="font-size:.55rem" onclick="removeKw(this)" aria-label="Remove keyword"></button>`;
    wrap.insertBefore(tag, e.target);
    e.target.value = '';
}

function removeKw(btn) { btn.closest('.kw-tag').remove(); }
</script>
@endsection