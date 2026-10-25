<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Admin' }} · D51 Admin</title>
    <style>
        :root { --graphite: #25282A; --warm: #F5F2EB; --brick: #B65C3A; --line: #d8d6d0; }
        * { box-sizing: border-box; }
        body { margin: 0; font: 15px/1.6 system-ui, "Noto Sans Thai", sans-serif; color: var(--graphite); background: var(--warm); }
        header { display: flex; flex-wrap: wrap; gap: 16px; align-items: center; padding: 12px 24px; background: var(--graphite); color: #fff; }
        header a, header button { color: #fff; text-decoration: none; background: none; border: 0; font: inherit; cursor: pointer; }
        main { max-width: 1100px; margin: 24px auto; padding: 0 16px; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 8px 10px; border-bottom: 1px solid var(--line); text-align: left; vertical-align: top; }
        label { display: block; margin-top: 12px; font-weight: 600; }
        input[type=text], input[type=email], input[type=password], input[type=number], input[type=url], input[type=datetime-local], select, textarea { width: 100%; padding: 8px; border: 1px solid var(--line); border-radius: 4px; font: inherit; background: #fff; }
        textarea { min-height: 120px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 0 24px; }
        .btn { display: inline-block; padding: 8px 16px; background: var(--brick); color: #fff; border: 0; border-radius: 4px; text-decoration: none; cursor: pointer; font: inherit; }
        .btn-link { background: none; color: var(--brick); padding: 0; border: 0; cursor: pointer; font: inherit; text-decoration: underline; }
        .flash { padding: 10px 14px; background: #fff; border-left: 4px solid var(--brick); margin-bottom: 16px; }
        .error { color: #a02c1c; margin: 4px 0 0; }
        .muted { color: #6b6d6a; }
        .row { display: flex; flex-wrap: wrap; gap: 12px; align-items: center; justify-content: space-between; }
        img.thumb { width: 120px; height: 80px; object-fit: cover; }
    </style>
</head>
<body>
    @auth
        <header>
            <strong>D51 Admin</strong>
            <a href="{{ route('admin.dashboard') }}">ภาพรวม</a>
            <a href="{{ route('admin.inquiries.index') }}">ข้อความติดต่อ</a>
            <a href="{{ route('admin.services.index') }}">บริการ</a>
            <a href="{{ route('admin.projects.index') }}">ผลงาน</a>
            <a href="{{ route('admin.settings.edit') }}">ตั้งค่า</a>
            <a href="{{ route('home', ['locale' => config('d51.default_locale')]) }}" target="_blank" rel="noopener">ดูเว็บไซต์</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="margin-left:auto">
                @csrf
                <button type="submit">ออกจากระบบ</button>
            </form>
        </header>
    @endauth
    <main>
        @if (session('status'))
            <div class="flash" role="status">{{ session('status') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
