{{-- resources/views/admin/settings/_tabs.blade.php --}}
@php
    $tabs = [
        'general' => ['label' => 'General',  'icon' => 'bi-sliders',   'route' => 'settings.general'],
        'seo'     => ['label' => 'SEO',       'icon' => 'bi-search',    'route' => 'settings.seo'],
        'social'  => ['label' => 'Social',    'icon' => 'bi-share',     'route' => 'settings.social'],
    ];
@endphp

<ul class="nav nav-tabs mb-4">
    @foreach($tabs as $key => $tab)
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-2 {{ $active === $key ? 'active fw-semibold' : 'text-muted' }}"
               href="{{ route($tab['route']) }}">
                <i class="bi {{ $tab['icon'] }}"></i>
                {{ $tab['label'] }}
            </a>
        </li>
    @endforeach
</ul>