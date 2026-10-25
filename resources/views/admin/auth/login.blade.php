@extends('admin.layout', ['title' => 'เข้าสู่ระบบ'])

@section('content')
    <form method="POST" action="{{ route('admin.login.store') }}" style="max-width:360px;margin:10vh auto">
        @csrf
        <h1>D51 Admin</h1>
        <label for="email">อีเมล</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        @error('email') <p class="error">{{ $message }}</p> @enderror
        <label for="password">รหัสผ่าน</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">
        <label style="font-weight:normal"><input type="checkbox" name="remember" value="1"> จดจำการเข้าสู่ระบบ</label>
        <p><button class="btn" type="submit">เข้าสู่ระบบ</button></p>
    </form>
@endsection
