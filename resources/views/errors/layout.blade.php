{{-- Self-contained error page: no database, routes or built assets, so it renders even when those fail. --}}
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>@yield('code') · D51 Construction</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 32px 20px; font: 17px/1.7 'Noto Sans Thai', system-ui, sans-serif; color: #fff; background: #25282a; }
        main { width: 100%; max-width: 640px; }
        .logo { position: relative; display: inline-block; font: 800 34px/0.9 system-ui, sans-serif; letter-spacing: -0.05em; }
        .logo::before { content: ''; position: absolute; top: 0; left: 0; width: 0.24em; height: 0.24em; background: #b65c3a; box-shadow: 0 0 0 0.05em #25282a; }
        .code { margin: 56px 0 8px; font: 800 96px/1 system-ui, sans-serif; letter-spacing: -0.04em; color: #b65c3a; }
        h1 { margin: 0; font-size: 30px; line-height: 1.35; }
        p { margin: 12px 0 0; color: #c9c8c3; }
        .en { margin-top: 4px; font-size: 15px; color: #9a9c98; }
        a { display: inline-flex; margin-top: 36px; padding: 14px 26px; background: #b65c3a; color: #fff; font-weight: 600; text-decoration: none; }
        a:hover { background: #9a4b2e; }
    </style>
</head>
<body>
    <main>
        <span class="logo">D51</span>
        <div class="code">@yield('code')</div>
        <h1>@yield('title_th')</h1>
        <p>@yield('text_th')</p>
        <p class="en">@yield('text_en')</p>
        @hasSection('link')
            <a href="{{ url('/') }}">@yield('link')</a>
        @endif
    </main>
</body>
</html>
