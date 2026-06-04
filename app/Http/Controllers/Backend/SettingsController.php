<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateSettingsRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    private const FILE_KEYS = ['favicon', 'logo', 'logo_dark', 'og_image'];
 
    private const JSON_KEYS = ['meta_keywords', 'social_links'];

    private const BOOL_KEYS = ['enable_sitemap', 'enable_jsonld'];

    public function general(): View
    {
        return view('settings.general', [
            'settings' => $this->loadGroup('general'),
            'appearance' => $this->loadGroup('appearance'),
        ]);
    }

    public function updateGeneral(UpdateSettingsRequest $request): RedirectResponse
    {
        $this->handleFiles($request, ['favicon', 'logo', 'logo_dark']);
        $this->handleDeleteFiles($request, ['favicon', 'logo', 'logo_dark']);
 
        $text = $request->only(['site_title', 'site_description', 'site_url', 'site_language', 'theme_color']);
        Setting::setMany($text);
 
        return back()->with('success', 'General settings saved.');
    }

    public function seo(): View
    {
        return view('settings.seo', [
            'settings' => $this->loadGroup('seo'),
        ]);
    }

    public function updateSeo(UpdateSettingsRequest $request): RedirectResponse
    {
        $text = $request->only([
            'meta_title', 'meta_description', 'canonical_url',
            'meta_author', 'robots', 'google_verify', 'bing_verify',
        ]);
        Setting::setMany($text);
 
        // Keywords as JSON array
        Setting::set('meta_keywords', $request->input('meta_keywords', []));
 
        // Booleans
        Setting::set('enable_sitemap', $request->boolean('enable_sitemap') ? '1' : '0');
        Setting::set('enable_jsonld',  $request->boolean('enable_jsonld')  ? '1' : '0');
 
        return back()->with('success', 'SEO settings saved.');
    }


    public function social(): View
    {
        return view('settings.social', [
            'settings'     => $this->loadGroup('social'),
            'social_links' => Setting::get('social_links', []),
            'og_image' => Setting::get('og_image', []),
        ]);
    }
 
    public function updateSocial(UpdateSettingsRequest $request): RedirectResponse
    {
        $this->handleFiles($request, ['og_image']);
        $this->handleDeleteFiles($request, ['og_image']);
 
        $text = $request->only(['og_title', 'og_description', 'og_type', 'twitter_card', 'twitter_handle']);
        Setting::setMany($text);
 
        Setting::set('social_links', $request->input('social_links', []));
 
        return back()->with('success', 'Social settings saved.');
    }


    public function deleteFile(Request $request, string $key): RedirectResponse
    {
        abort_unless(in_array($key, self::FILE_KEYS), 422, 'Invalid file key.');
 
        Setting::deleteFile($key);
 
        return back()->with('success', ucfirst($key) . ' removed.');
    }


    private function handleFiles(Request $request, array $keys): void
    {
        foreach ($keys as $key) {
            if ($request->hasFile($key)) {
                Setting::storeFile($key, $request->file($key));
            }
        }
    }


    private function handleDeleteFiles(Request $request, array $keys): void
    {
        foreach ($keys as $key) {
            if ($request->boolean("remove_{$key}")) {
                Setting::deleteFile($key);
            }
        }
    }


    private function loadGroup(string $group): array
    {
        $raw = Setting::group($group);
 
        // Decode JSON fields
        foreach (self::JSON_KEYS as $jk) {
            if (isset($raw[$jk])) {
                $raw[$jk] = json_decode($raw[$jk], true) ?? [];
            }
        }
 
        // Cast booleans
        foreach (self::BOOL_KEYS as $bk) {
            if (array_key_exists($bk, $raw)) {
                $raw[$bk] = (bool) $raw[$bk];
            }
        }
 
        return $raw;
    }



}
