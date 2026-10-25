@props(['name'])
<svg {{ $attributes->class('icon') }} viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">
    @switch($name)
        @case('arrow') <path d="M4 12h15M13 6l6 6-6 6"/> @break
        @case('arrow-left') <path d="M20 12H5M11 6l-6 6 6 6"/> @break
        @case('scope') <path d="M7 3h7l4 4v14H7z"/><path d="M14 3v4h4M10 11h5M10 14.5h5M10 18h3"/> @break
        @case('build') <circle cx="12" cy="12" r="3"/><path d="M12 2.5v3M12 18.5v3M21.5 12h-3M5.5 12h-3M18.7 5.3l-2.1 2.1M7.4 16.6l-2.1 2.1M18.7 18.7l-2.1-2.1M7.4 7.4 5.3 5.3"/> @break
        @case('progress') <path d="M4 5h16v11H9l-5 4z"/><path d="M8 9.5h8M8 12.5h5"/> @break
        @case('check') <path d="m5 12.5 4.5 4.5L19 7.5"/> @break
        @case('menu') <path d="M4 7h16M4 12h16M4 17h16"/> @break
        @case('close') <path d="M6 6l12 12M18 6 6 18"/> @break
        @case('phone') <path d="M5 4h4l2 5-2.5 1.5a11 11 0 0 0 5 5L15 13l5 2v4a1 1 0 0 1-1 1A16 16 0 0 1 4 5a1 1 0 0 1 1-1z"/> @break
        @case('mail') <rect x="3" y="5" width="18" height="14" rx="1"/><path d="m3.5 6 8.5 7 8.5-7"/> @break
        @case('pin') <path d="M12 21s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="9" r="2.5"/> @break
        @case('chat') <path d="M4 5h16v11H9l-5 4z"/> @break
        @case('external') <path d="M14 4h6v6M20 4l-9 9M18 14v6H4V6h6"/> @break
        @case('dashboard') <rect x="3.5" y="3.5" width="7" height="7"/><rect x="13.5" y="3.5" width="7" height="4"/><rect x="13.5" y="10.5" width="7" height="10"/><rect x="3.5" y="13.5" width="7" height="7"/> @break
        @case('inbox') <path d="M3.5 13.5 6 5h12l2.5 8.5V19h-17z"/><path d="M3.5 13.5H9l1 2h4l1-2h5.5"/> @break
        @case('layers') <path d="m12 3 9 5-9 5-9-5z"/><path d="m3 13 9 5 9-5"/> @break
        @case('image') <rect x="3.5" y="4.5" width="17" height="15"/><circle cx="9" cy="10" r="1.8"/><path d="m4 18 5.5-5 4 3.5 2.5-2 4 3.5"/> @break
        @case('settings') <path d="M4 7h10M18 7h2M4 17h4M12 17h8"/><circle cx="16" cy="7" r="2"/><circle cx="10" cy="17" r="2"/> @break
        @case('logout') <path d="M14 4h6v16h-6M10 8l-4 4 4 4M6 12h10"/> @break
        @case('alert') <path d="M12 4 2.5 20h19z"/><path d="M12 10v4.5M12 17.5v.01"/> @break
        @case('plus') <path d="M12 5v14M5 12h14"/> @break
    @endswitch
</svg>
