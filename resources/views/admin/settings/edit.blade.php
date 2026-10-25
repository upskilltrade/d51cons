@extends('admin.layout', ['title' => 'ตั้งค่า'])

@section('content')
    <h1>ตั้งค่า</h1>
    <p class="muted">ช่องที่เว้นว่างจะไม่แสดงบนเว็บไซต์ กรอกเฉพาะข้อมูลจริงที่ยืนยันแล้ว</p>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        <div class="grid">
            @foreach ($settings as $key => $value)
                @include('admin.field', [
                    'name' => $key,
                    'label' => $key,
                    'value' => $value,
                    'type' => match (true) {
                        str_ends_with($key, '_url') => 'url',
                        $key === 'email' => 'email',
                        str_starts_with($key, 'about_'), str_starts_with($key, 'address_'), str_starts_with($key, 'seo_description_') => 'textarea',
                        default => 'text',
                    },
                ])
            @endforeach
        </div>
        <p><button class="btn" type="submit">บันทึก</button></p>
    </form>
@endsection
