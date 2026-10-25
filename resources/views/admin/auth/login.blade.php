@extends('admin.layout', ['title' => 'เข้าสู่ระบบ'])

@section('content')
    <div class="auth">
        <div class="auth__art" style="--auth-image: url('{{ asset('images/concepts/hero-pool-villa-1280.webp') }}')">
            <span class="brand"><span class="brand__mark">D51</span><span class="brand__label">Construction</span></span>
            <p class="auth__tagline">Built with<br>precision<span>.</span></p>
        </div>
        <div class="auth__panel">
            <form class="auth__form" method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <h1>เข้าสู่ระบบผู้ดูแล</h1>
                <p>จัดการบริการ ผลงาน และข้อความติดต่อของ D51 Construction</p>

                <div @class(['field', 'field--invalid' => $errors->has('email')])>
                    <label for="email">อีเมล</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email') <p class="error">{{ $message }}</p> @enderror
                </div>
                <div class="field">
                    <label for="password">รหัสผ่าน</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password">
                </div>
                <div class="field">
                    <label class="check"><input type="checkbox" name="remember" value="1"> จดจำการเข้าสู่ระบบ</label>
                </div>
                <button class="btn btn--block" type="submit">เข้าสู่ระบบ <x-icon name="arrow" /></button>
            </form>
        </div>
    </div>
@endsection
