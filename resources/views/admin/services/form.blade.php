@extends('admin.layout', ['title' => $service->exists ? $service->title_th : 'เพิ่มบริการ'])

@section('content')
    <a class="crumb" href="{{ route('admin.services.index') }}"><x-icon name="arrow-left" /> บริการ</a>
    <div class="page-head">
        <h1>{{ $service->exists ? $service->title_th : 'เพิ่มบริการ' }}</h1>
        @if ($service->exists && $service->is_published)
            <a class="btn btn--secondary" href="{{ route('services.show', ['locale' => config('d51.default_locale'), 'service' => $service]) }}" target="_blank" rel="noopener"><x-icon name="external" /> ดูบนเว็บไซต์</a>
        @endif
    </div>

    <form class="card" method="POST" enctype="multipart/form-data" action="{{ $service->exists ? route('admin.services.update', $service) : route('admin.services.store') }}">
        @csrf
        @if ($service->exists) @method('PUT') @endif

        <div class="card__body">
            <div class="fieldset">
                <h2 class="section-title">เนื้อหา</h2>
                <p>กรอกภาษาไทยเป็นหลัก ช่องภาษาอังกฤษที่ว่างจะแสดงภาษาไทยแทน</p>
                <div class="form-grid">
                    @include('admin.field', ['name' => 'title_th', 'label' => 'ชื่อ (TH)', 'value' => $service->title_th])
                    @include('admin.field', ['name' => 'title_en', 'label' => 'ชื่อ (EN)', 'value' => $service->title_en])
                    @include('admin.field', ['name' => 'summary_th', 'label' => 'สรุปสั้น (TH)', 'value' => $service->summary_th, 'type' => 'textarea'])
                    @include('admin.field', ['name' => 'summary_en', 'label' => 'สรุปสั้น (EN)', 'value' => $service->summary_en, 'type' => 'textarea'])
                    @include('admin.field', ['name' => 'body_th', 'label' => 'รายละเอียด (TH)', 'value' => $service->body_th, 'type' => 'textarea', 'help' => 'เว้นบรรทัดว่างเพื่อขึ้นย่อหน้าใหม่'])
                    @include('admin.field', ['name' => 'body_en', 'label' => 'รายละเอียด (EN)', 'value' => $service->body_en, 'type' => 'textarea'])
                </div>
            </div>

            <div class="fieldset">
                <h2 class="section-title">ภาพและการแสดงผล</h2>
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="image">ภาพ (JPEG/PNG/WebP)</label>
                        @if ($service->imageUrl())
                            <div class="media-preview">
                                <img src="{{ $service->imageUrl() }}" alt="">
                                <label class="check"><input type="checkbox" name="remove_image" value="1"> ลบภาพ</label>
                            </div>
                        @endif
                        <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp">
                        @error('image') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    @include('admin.field', ['name' => 'slug', 'label' => 'slug (ภาษาอังกฤษ ใช้ใน URL)', 'value' => $service->slug])
                    @include('admin.field', ['name' => 'sort_order', 'label' => 'ลำดับ', 'value' => $service->sort_order, 'type' => 'number'])
                    <div class="span-2 toggle-box">
                        <label class="check">
                            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $service->is_published))>
                            <span>เผยแพร่บนเว็บไซต์<small>ยืนยันข้อความว่าเป็นบริการจริงก่อนเผยแพร่</small></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="card__foot">
            <span class="muted">การเปลี่ยนแปลงมีผลทันทีหลังบันทึก</span>
            <button class="btn" type="submit">บันทึก</button>
        </div>
    </form>

    @if ($service->exists)
        <form class="danger-zone" method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('ลบบริการนี้?')">
            @csrf
            @method('DELETE')
            <p>ลบบริการนี้ออกจากระบบ ผลงานที่ผูกไว้จะไม่ถูกลบ</p>
            <button class="btn btn--danger" type="submit">ลบบริการ</button>
        </form>
    @endif
@endsection
