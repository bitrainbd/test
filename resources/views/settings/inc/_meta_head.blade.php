@php
    use App\Models\Setting;
@endphp

{{-- Basic --}}
<meta charset="UTF-8">
<meta name="viewport" content="{{ Setting::get('viewport', 'width=device-width, initial-scale=1') }}">
<title>{{ Setting::get('meta_title') ?: Setting::get('site_title', config('app.name')) }}</title>
<meta name="description" content="{{ Setting::get('meta_description') }}">

@if(Setting::get('meta_keywords'))
<meta name="keywords" content="{{ implode(', ', (array) Setting::get('meta_keywords')) }}">
@endif

@if(Setting::get('meta_author'))
<meta name="author" content="{{ Setting::get('meta_author') }}">
@endif

<meta name="robots" content="{{ Setting::get('robots', 'index,follow') }}">

@if(Setting::get('theme_color'))
<meta name="theme-color" content="{{ Setting::get('theme_color') }}">
@endif

@if(Setting::get('canonical_url'))
<link rel="canonical" href="{{ Setting::get('canonical_url') }}">
@endif

{{-- Favicon --}}
@if(Setting::get('favicon'))
<link rel="icon" href="{{ Setting::get('favicon') }}">
@endif

{{-- Open Graph --}}
<meta property="og:type"        content="{{ Setting::get('og_type', 'website') }}">
<meta property="og:title"       content="{{ Setting::get('og_title') ?: Setting::get('site_title') }}">
<meta property="og:description" content="{{ Setting::get('og_description') ?: Setting::get('meta_description') }}">
<meta property="og:url"         content="{{ Setting::get('canonical_url') ?: Setting::get('site_url', url()->current()) }}">
@if(Setting::get('og_image'))
<meta property="og:image" content="{{ Setting::get('og_image') }}">
@endif

{{-- Twitter Card --}}
<meta name="twitter:card"        content="{{ Setting::get('twitter_card', 'summary_large_image') }}">
@if(Setting::get('twitter_handle'))
<meta name="twitter:site"        content="@{{ ltrim(Setting::get('twitter_handle'), '@') }}">
@endif
<meta name="twitter:title"       content="{{ Setting::get('og_title') ?: Setting::get('site_title') }}">
<meta name="twitter:description" content="{{ Setting::get('og_description') ?: Setting::get('meta_description') }}">
@if(Setting::get('og_image'))
<meta name="twitter:image" content="{{ Setting::get('og_image') }}">
@endif

{{-- Verification --}}
@if(Setting::get('google_verify'))
<meta name="google-site-verification" content="{{ Setting::get('google_verify') }}">
@endif
@if(Setting::get('bing_verify'))
<meta name="msvalidate.01" content="{{ Setting::get('bing_verify') }}">
@endif

{{-- JSON-LD --}}
@if(\App\Models\Setting::get('enable_jsonld'))
@php
    $jsonLd = json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'WebSite',
        'name'        => \App\Models\Setting::get('site_title'),
        'url'         => \App\Models\Setting::get('site_url', config('app.url')),
        'description' => \App\Models\Setting::get('meta_description'),
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
@endphp
<script type="application/ld+json">{!! $jsonLd !!}</script>
@endif