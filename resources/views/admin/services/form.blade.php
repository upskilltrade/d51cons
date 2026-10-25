@extends('admin.layout', ['title' => $service->exists ? $service->title_th : 'เพิ่มบริการ'])

@section('content')
    <h1>{{ $service->exists ? $service->title_th : 'เพิ่มบริการ' }}</h1>

    <form method="POST" enctype="multipart/form-data" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
        @csrf
        @if ($service->exists) @method('PUT') @endif

        <div class="grid">
            @include('admin.field', ['name' => 'slug', 'label' => 'slug (ภาษาอังกฤษ ใช้ใน URL)', 'value' => $service->slug])
            @include('admin.field', ['name' => 'sort_order', 'label' => 'ลำดับ', 'value' => $service->sort_order, 'type' => 'number'])
            @include('admin.field', ['name' => 'title_th', 'label' => 'ชื่อ (TH)', 'value' => $service->title_th])
            @include('admin.field', ['name' => 'title_en', 'label' => 'ชื่อ (EN)', 'value' => $service->title_en])
            @include('admin.field', ['name' => 'summary_th', 'label' => 'สรุปสั้น (TH)', 'value' => $service->summary_th, 'type' => 'textarea'])
            @include('admin.field', ['name' => 'summary_en', 'label' => 'สรุปสั้น (EN)', 'value' => $service->summary_en, 'type' => 'textarea'])
            @include('admin.field', ['name' => 'body_th', 'label' => 'รายละเอียด (TH)', 'value' => $service->body_th, 'type' => 'textarea'])
            @include('admin.field', ['name' => 'body_en', 'label' => 'รายละเอียด (EN)', 'value' => $service->body_en, 'type' => 'textarea'])
        </div>

        <label for="image">ภาพ (JPEG/PNG/WebP)</label>
        @if ($service->imageUrl())
            <img class="thumb" src="{{ $service->imageUrl() }}" alt="">
            <label style="font-weight:normal"><input type="checkbox" name="remove_image" value="1"> ลบภาพ</label>
        @endif
        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
        @error('image') <p class="error">{{ $message }}</p> @enderror

        <label style="font-weight:normal"><input type="checkbox" name="is_published" value="1" @checked(old('is_published', $service->is_published))> เผยแพร่บนเว็บไซต์</label>

        <p><button class="btn" type="submit">บันทึก</button></p>
    </form>

    @if ($service->exists)
        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('ลบบริการนี้?')">
            @csrf
            @method('DELETE')
            <button class="btn-link" type="submit">ลบบริการ</button>
        </form>
    @endif
@endsection
