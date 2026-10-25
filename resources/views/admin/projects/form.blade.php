@extends('admin.layout', ['title' => $project->exists ? $project->title_th : 'เพิ่มผลงาน'])

@section('content')
    <h1>{{ $project->exists ? $project->title_th : 'เพิ่มผลงาน' }}</h1>

    <form method="POST" enctype="multipart/form-data" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}">
        @csrf
        @if ($project->exists) @method('PUT') @endif

        <div class="grid">
            @include('admin.field', ['name' => 'slug', 'label' => 'slug (ภาษาอังกฤษ ใช้ใน URL)', 'value' => $project->slug])
            @include('admin.field', ['name' => 'sort_order', 'label' => 'ลำดับ', 'value' => $project->sort_order, 'type' => 'number'])
            @include('admin.field', ['name' => 'title_th', 'label' => 'ชื่อ (TH)', 'value' => $project->title_th])
            @include('admin.field', ['name' => 'title_en', 'label' => 'ชื่อ (EN)', 'value' => $project->title_en])
            @include('admin.field', ['name' => 'summary_th', 'label' => 'สรุปสั้น (TH)', 'value' => $project->summary_th, 'type' => 'textarea'])
            @include('admin.field', ['name' => 'summary_en', 'label' => 'สรุปสั้น (EN)', 'value' => $project->summary_en, 'type' => 'textarea'])
            @include('admin.field', ['name' => 'body_th', 'label' => 'รายละเอียด (TH)', 'value' => $project->body_th, 'type' => 'textarea'])
            @include('admin.field', ['name' => 'body_en', 'label' => 'รายละเอียด (EN)', 'value' => $project->body_en, 'type' => 'textarea'])
            @include('admin.field', ['name' => 'location', 'label' => 'สถานที่ (แสดงเท่าที่เจ้าของงานอนุญาต)', 'value' => $project->location])
            @include('admin.field', ['name' => 'area_sqm', 'label' => 'พื้นที่ (ตร.ม.)', 'value' => $project->area_sqm, 'type' => 'number'])
        </div>

        <div class="grid">
            <div>
                <label for="service_id">ประเภทบริการ</label>
                <select id="service_id" name="service_id">
                    <option value="">-</option>
                    @foreach ($services as $service)
                        <option value="{{ $service->id }}" @selected((int) old('service_id', $project->service_id) === $service->id)>{{ $service->title_th }}</option>
                    @endforeach
                </select>
                @error('service_id') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="status">สถานะงาน</label>
                <select id="status" name="status">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $project->status?->value) === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
                @error('status') <p class="error">{{ $message }}</p> @enderror
            </div>
            @include('admin.field', ['name' => 'published_at', 'label' => 'วันที่เผยแพร่ (ว่าง = ฉบับร่าง)', 'value' => $project->published_at?->format('Y-m-d\TH:i'), 'type' => 'datetime-local'])
        </div>

        <label style="font-weight:normal">
            <input type="checkbox" name="is_concept" value="1" @checked(old('is_concept', $project->is_concept))>
            เป็นภาพแนวคิด (ไม่ใช่ผลงานจริง) จะแสดงป้าย “ภาพแนวคิดประกอบ” และแยกจากผลงานจริง
        </label>

        <label for="cover">ภาพหน้าปก (JPEG/PNG/WebP)</label>
        @if ($project->coverUrl())
            <img class="thumb" src="{{ $project->coverUrl() }}" alt="">
        @endif
        <input id="cover" name="cover" type="file" accept="image/jpeg,image/png,image/webp">
        @error('cover') <p class="error">{{ $message }}</p> @enderror

        <p><button class="btn" type="submit">บันทึก</button></p>
    </form>

    @if ($project->exists)
        <h2>แกลเลอรี</h2>
        <form method="POST" enctype="multipart/form-data" action="{{ route('admin.projects.images.store', $project) }}">
            @csrf
            <input name="images[]" type="file" multiple accept="image/jpeg,image/png,image/webp">
            <button class="btn" type="submit">อัปโหลด</button>
            @error('images') <p class="error">{{ $message }}</p> @enderror
            @error('images.*') <p class="error">{{ $message }}</p> @enderror
        </form>

        <table>
            @foreach ($project->images as $image)
                <tr>
                    <td><img class="thumb" src="{{ $image->url() }}" alt=""></td>
                    <td>
                        <form method="POST" action="{{ route('admin.projects.images.update', [$project, $image]) }}" class="grid">
                            @csrf
                            @method('PUT')
                            <input type="text" name="alt_th" value="{{ $image->alt_th }}" placeholder="คำอธิบายภาพ (TH)">
                            <input type="text" name="alt_en" value="{{ $image->alt_en }}" placeholder="Alt text (EN)">
                            <input type="text" name="caption_th" value="{{ $image->caption_th }}" placeholder="คำบรรยาย (TH)">
                            <input type="text" name="caption_en" value="{{ $image->caption_en }}" placeholder="Caption (EN)">
                            <input type="number" name="sort_order" value="{{ $image->sort_order }}" min="0">
                            <button class="btn" type="submit">บันทึกภาพ</button>
                        </form>
                        <form method="POST" action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" onsubmit="return confirm('ลบภาพนี้?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn-link" type="submit">ลบภาพ</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        <form method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('ลบผลงานนี้และภาพทั้งหมด?')" style="margin-top:24px">
            @csrf
            @method('DELETE')
            <button class="btn-link" type="submit">ลบผลงาน</button>
        </form>
    @endif
@endsection
