@extends('admin.layout', ['title' => 'ตั้งค่า'])

@php
    $groups = [
        'ข้อมูลบริษัท' => ['legal_name_th' => 'ชื่อนิติบุคคล (TH)', 'legal_name_en' => 'ชื่อนิติบุคคล (EN)', 'registration_no' => 'เลขทะเบียนนิติบุคคล'],
        'ช่องทางติดต่อ' => ['phone' => 'เบอร์โทรศัพท์', 'email' => 'อีเมล', 'line_url' => 'ลิงก์ LINE', 'whatsapp_url' => 'ลิงก์ WhatsApp', 'facebook_url' => 'ลิงก์ Facebook', 'instagram_url' => 'ลิงก์ Instagram'],
        'ที่ตั้งและพื้นที่ให้บริการ' => ['address_th' => 'ที่อยู่ (TH)', 'address_en' => 'ที่อยู่ (EN)', 'map_url' => 'ลิงก์แผนที่', 'service_area_th' => 'พื้นที่ให้บริการ (TH)', 'service_area_en' => 'พื้นที่ให้บริการ (EN)'],
        'หน้าเกี่ยวกับเรา' => ['about_th' => 'ข้อความเกี่ยวกับเรา (TH)', 'about_en' => 'ข้อความเกี่ยวกับเรา (EN)'],
        'SEO' => ['seo_title_th' => 'ชื่อเว็บไซต์ (TH)', 'seo_title_en' => 'ชื่อเว็บไซต์ (EN)', 'seo_description_th' => 'คำอธิบายเว็บไซต์ (TH)', 'seo_description_en' => 'คำอธิบายเว็บไซต์ (EN)'],
    ];
    // Any allowlisted key without a group still gets a field.
    $grouped = collect($groups)->flatMap(fn ($fields) => array_keys($fields))->all();
    $rest = array_diff(array_keys($settings), $grouped);
    if ($rest) {
        $groups['อื่น ๆ'] = array_combine($rest, $rest);
    }
@endphp

@section('content')
    <div class="page-head">
        <div>
            <h1>ตั้งค่า</h1>
            <p>ช่องที่เว้นว่างจะไม่แสดงบนเว็บไซต์ กรอกเฉพาะข้อมูลจริงที่ยืนยันแล้ว</p>
        </div>
    </div>

    <form class="card" method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        <div class="card__body">
            @foreach ($groups as $group => $fields)
                <div class="fieldset">
                    <h2 class="section-title">{{ $group }}</h2>
                    <div class="form-grid">
                        @foreach ($fields as $key => $label)
                            @continue(! array_key_exists($key, $settings))
                            @include('admin.field', [
                                'name' => $key,
                                'label' => $label,
                                'value' => $settings[$key],
                                'type' => match (true) {
                                    str_ends_with($key, '_url') => 'url',
                                    $key === 'email' => 'email',
                                    str_starts_with($key, 'about_'), str_starts_with($key, 'address_'), str_starts_with($key, 'seo_description_') => 'textarea',
                                    default => 'text',
                                },
                                'help' => str_ends_with($key, '_url') ? 'ต้องขึ้นต้นด้วย https://' : null,
                            ])
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
        <div class="card__foot">
            <span class="muted">การเปลี่ยนแปลงมีผลทันทีหลังบันทึก</span>
            <button class="btn" type="submit">บันทึก</button>
        </div>
    </form>
@endsection
