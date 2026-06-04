<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // General
            'site_title'       => ['nullable', 'string', 'max:100'],
            'site_description' => ['nullable', 'string', 'max:500'],
            'site_url'         => ['nullable', 'url', 'max:255'],
            'site_language'    => ['nullable', 'string', 'max:10'],
            'theme_color'      => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
 
            // Appearance / files
            'favicon'          => ['nullable', 'image', 'mimes:ico,png,svg,jpg,webp', 'max:512'],   // 512 KB
            'logo'             => ['nullable', 'image', 'mimes:png,svg,jpg,webp', 'max:2048'],
            'logo_dark'        => ['nullable', 'image', 'mimes:png,svg,jpg,webp', 'max:2048'],
            'og_image'         => ['nullable', 'image', 'mimes:jpg,png,webp', 'max:4096'],
 
            // SEO
            'meta_title'       => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:160'],
            'meta_keywords'    => ['nullable', 'array'],
            'meta_keywords.*'  => ['string', 'max:50'],
            'canonical_url'    => ['nullable', 'url', 'max:255'],
            'meta_author'      => ['nullable', 'string', 'max:100'],
            'robots'           => ['nullable', 'string', 'in:index,follow,noindex,follow,noindex,nofollow,index,nofollow'],
            'google_verify'    => ['nullable', 'string', 'max:255'],
            'bing_verify'      => ['nullable', 'string', 'max:255'],
            'enable_sitemap'   => ['nullable', 'boolean'],
            'enable_jsonld'    => ['nullable', 'boolean'],
 
            // Social / OG
            'og_title'         => ['nullable', 'string', 'max:95'],
            'og_description'   => ['nullable', 'string', 'max:300'],
            'og_type'          => ['nullable', 'string', 'in:website,article,product,profile'],
            'twitter_card'     => ['nullable', 'string', 'in:summary_large_image,summary,app,player'],
            'twitter_handle'   => ['nullable', 'string', 'max:50'],
            'social_links'     => ['nullable', 'array'],
            'social_links.*'   => ['nullable', 'url', 'max:255'],
        ];
    }
 
    public function messages(): array
    {
        return [
            'theme_color.regex'    => 'Theme color must be a valid hex color (e.g. #1a1a1a).',
            'favicon.max'          => 'Favicon must be smaller than 512 KB.',
            'meta_title.max'       => 'Meta title should be 70 characters or fewer for best SEO.',
            'meta_description.max' => 'Meta description should be 160 characters or fewer for best SEO.',
        ];
    }
 
    protected function prepareForValidation(): void
    {
        $this->merge([
            'enable_sitemap' => $this->boolean('enable_sitemap'),
            'enable_jsonld'  => $this->boolean('enable_jsonld'),
        ]);
    }
}
