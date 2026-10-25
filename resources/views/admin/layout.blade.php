@php
    $nav = [
        ['admin.dashboard', 'ภาพรวม', 'dashboard', 'admin.dashboard'],
        ['admin.inquiries.index', 'ข้อความติดต่อ', 'inbox', 'admin.inquiries.*'],
        ['admin.services.index', 'บริการ', 'layers', 'admin.services.*'],
        ['admin.projects.index', 'ผลงาน', 'image', 'admin.projects.*'],
        ['admin.settings.edit', 'ตั้งค่า', 'settings', 'admin.settings.*'],
    ];
@endphp
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $title ?? 'Admin' }} · D51 Admin</title>
    @vite('resources/css/admin.css')
</head>
<body>
    @auth
        @php($newCount = \App\Models\Inquiry::query()->where('status', \App\Enums\InquiryStatus::New)->count())
        <div class="shell">
            <aside class="sidebar">
                <a class="brand" href="{{ route('admin.dashboard') }}">
                    <span class="brand__mark">D51</span>
                    <span class="brand__label">Admin</span>
                </a>
                <nav aria-label="เมนูผู้ดูแล">
                    <ul class="nav">
                        @foreach ($nav as [$route, $label, $icon, $pattern])
                            <li>
                                <a href="{{ route($route) }}" @if (request()->routeIs($pattern)) aria-current="page" @endif>
                                    <x-icon :name="$icon" /> {{ $label }}
                                    @if ($route === 'admin.inquiries.index' && $newCount)
                                        <span class="count" aria-label="{{ $newCount }} ข้อความใหม่">{{ $newCount }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a href="{{ route('home', ['locale' => config('d51.default_locale')]) }}" target="_blank" rel="noopener">
                                <x-icon name="external" /> ดูเว็บไซต์
                            </a>
                        </li>
                    </ul>
                </nav>
                <div class="sidebar__foot">
                    <div class="sidebar__user">{{ auth()->user()->email }}</div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="nav">
                        @csrf
                        <button type="submit"><x-icon name="logout" /> ออกจากระบบ</button>
                    </form>
                </div>
            </aside>
            <main class="main">
                <div class="main__inner">
                    @if (session('status'))
                        <div class="flash" role="status"><x-icon name="check" /> {{ session('status') }}</div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    @else
        @yield('content')
    @endauth
</body>
</html>
