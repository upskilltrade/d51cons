<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Public pages live under /{locale}. Thai is the default and content
    | falls back to Thai when an English field is empty.
    |
    */

    'locales' => ['th', 'en'],

    'default_locale' => 'th',

    /*
    |--------------------------------------------------------------------------
    | Inquiries
    |--------------------------------------------------------------------------
    */

    'inquiry_to' => env('INQUIRY_TO_EMAIL'),

    'budget_ranges' => [
        'under_3m',
        '3m_10m',
        '10m_30m',
        'over_30m',
        'not_sure',
    ],

    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Only these keys can be stored in the settings table. Add a key here
    | when a page actually uses it; empty values are hidden on public pages.
    |
    */

    'settings' => [
        'legal_name_th',
        'legal_name_en',
        'registration_no',
        'phone',
        'email',
        'line_url',
        'whatsapp_url',
        'facebook_url',
        'instagram_url',
        'address_th',
        'address_en',
        'map_url',
        'service_area_th',
        'service_area_en',
        'about_th',
        'about_en',
        'seo_title_th',
        'seo_title_en',
        'seo_description_th',
        'seo_description_en',
    ],

    /*
    |--------------------------------------------------------------------------
    | Uploads
    |--------------------------------------------------------------------------
    */

    'uploads' => [
        'max_kb' => 8192,
        'max_dimension' => 6000,
    ],

    /*
    |--------------------------------------------------------------------------
    | Indexing
    |--------------------------------------------------------------------------
    |
    | Staging must set SITE_NOINDEX=true so every response carries
    | X-Robots-Tag: noindex. Production leaves it false.
    |
    */

    'noindex' => (bool) env('SITE_NOINDEX', false),

];
