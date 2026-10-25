@extends('admin.layout', ['title' => $project->exists ? $project->title_th : 'เพิ่มผลงาน'])

@section('content')
    <a class="crumb" href="{{ route('admin.projects.index') }}"><x-icon name="arrow-left" /> ผลงาน</a>
    <div class="page-head">
        <h1>{{ $project->exists ? $project->title_th : 'เพิ่มผลงาน' }}</h1>
        @if ($project->exists && $project->isPublished())
            <a class="btn btn--secondary" href="{{ route('projects.show', ['locale' => config('d51.default_locale'), 'project' => $project]) }}" target="_blank" rel="noopener"><x-icon name="external" /> ดูบนเว็บไซต์</a>
        @endif
    </div>

    <form class="card" method="POST" enctype="multipart/form-data" action="{{ $project->exists ? route('admin.projects.update', $project) : route('admin.projects.store') }}">
        @csrf
        @if ($project->exists) @method('PUT') @endif

        <div class="card__body">
            <div class="fieldset">
                <h2 class="section-title">เนื้อหา</h2>
                <p>กรอกภาษาไทยเป็นหลัก ช่องภาษาอังกฤษที่ว่างจะแสดงภาษาไทยแทน</p>
                <div class="form-grid">
                    @include('admin.field', ['name' => 'title_th', 'label' => 'ชื่อ (TH)', 'value' => $project->title_th])
                    @include('admin.field', ['name' => 'title_en', 'label' => 'ชื่อ (EN)', 'value' => $project->title_en])
                    @include('admin.field', ['name' => 'summary_th', 'label' => 'สรุปสั้น (TH)', 'value' => $project->summary_th, 'type' => 'textarea'])
                    @include('admin.field', ['name' => 'summary_en', 'label' => 'สรุปสั้น (EN)', 'value' => $project->summary_en, 'type' => 'textarea'])
                    @include('admin.field', ['name' => 'body_th', 'label' => 'รายละเอียด (TH)', 'value' => $project->body_th, 'type' => 'textarea', 'help' => 'เว้นบรรทัดว่างเพื่อขึ้นย่อหน้าใหม่'])
                    @include('admin.field', ['name' => 'body_en', 'label' => 'รายละเอียด (EN)', 'value' => $project->body_en, 'type' => 'textarea'])
                </div>
            </div>

            <div class="fieldset">
                <h2 class="section-title">ข้อมูลโครงการ</h2>
                <div class="form-grid form-grid--3">
                    <div @class(['field', 'field--invalid' => $errors->has('service_id')])>
                        <label for="service_id">ประเภทบริการ</label>
                        <select id="service_id" name="service_id">
                            <option value="">-</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}" @selected((int) old('service_id', $project->service_id) === $service->id)>{{ $service->title_th }}</option>
                            @endforeach
                        </select>
                        @error('service_id') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div @class(['field', 'field--invalid' => $errors->has('status')])>
                        <label for="status">สถานะงาน</label>
                        <select id="status" name="status">
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" @selected(old('status', $project->status?->value) === $status->value)>{{ $status->label() }}</option>
                            @endforeach
                        </select>
                        @error('status') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    @include('admin.field', ['name' => 'area_sqm', 'label' => 'พื้นที่ (ตร.ม.)', 'value' => $project->area_sqm, 'type' => 'number'])
                    @include('admin.field', ['name' => 'location', 'label' => 'สถานที่', 'value' => $project->location, 'help' => 'แสดงเท่าที่เจ้าของงานอนุญาต', 'class' => 'span-2'])
                </div>
            </div>

            <div class="fieldset">
                <h2 class="section-title">ภาพและการเผยแพร่</h2>
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="cover">ภาพหน้าปก (JPEG/PNG/WebP)</label>
                        @if ($project->coverUrl())
                            <div class="media-preview"><img src="{{ $project->coverUrl() }}" alt=""></div>
                        @endif
                        <input id="cover" name="cover" type="file" accept="image/jpeg,image/png,image/webp">
                        @error('cover') <p class="error">{{ $message }}</p> @enderror
                    </div>
                    <div class="span-2 toggle-box">
                        <label class="check">
                            <input type="checkbox" name="is_concept" value="1" @checked(old('is_concept', $project->is_concept))>
                            <span>เป็นภาพแนวคิด (ไม่ใช่ผลงานจริง)<small>จะแสดงป้าย “ภาพแนวคิดประกอบ” และแยกจากผลงานจริง</small></span>
                        </label>
                    </div>
                    @include('admin.field', ['name' => 'published_at', 'label' => 'วันที่เผยแพร่', 'value' => $project->published_at?->format('Y-m-d\TH:i'), 'type' => 'datetime-local', 'help' => 'เว้นว่าง = ฉบับร่าง, วันในอนาคต = ตั้งเวลา'])
                    @include('admin.field', ['name' => 'sort_order', 'label' => 'ลำดับ', 'value' => $project->sort_order, 'type' => 'number'])
                    @include('admin.field', ['name' => 'slug', 'label' => 'slug (ภาษาอังกฤษ ใช้ใน URL)', 'value' => $project->slug, 'class' => 'span-2'])
                </div>
            </div>
        </div>
        <div class="card__foot">
            <span class="muted">การเปลี่ยนแปลงมีผลทันทีหลังบันทึก</span>
            <button class="btn" type="submit">บันทึก</button>
        </div>
    </form>

    @if ($project->exists)
        <section class="card">
            <div class="card__head">
                <h2>แกลเลอรี</h2>
                <span class="muted">{{ $project->images->count() }} ภาพ</span>
            </div>
            <div class="card__body">
                <form class="upload-row" method="POST" enctype="multipart/form-data" action="{{ route('admin.projects.images.store', $project) }}">
                    @csrf
                    <input name="images[]" type="file" multiple accept="image/jpeg,image/png,image/webp" aria-label="เลือกภาพ">
                    <button class="btn" type="submit"><x-icon name="plus" /> อัปโหลด</button>
                </form>
                @error('images') <p class="error">{{ $message }}</p> @enderror
                @error('images.*') <p class="error">{{ $message }}</p> @enderror

                @if ($project->images->isNotEmpty())
                    <div class="gallery-list" style="margin-top:20px">
                        @foreach ($project->images as $image)
                            <div class="gallery-item">
                                <img src="{{ $image->url() }}" alt="">
                                <div>
                                    <form method="POST" action="{{ route('admin.projects.images.update', [$project, $image]) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-grid">
                                            <input type="text" name="alt_th" value="{{ $image->alt_th }}" placeholder="คำอธิบายภาพ (TH)" aria-label="คำอธิบายภาพ (TH)">
                                            <input type="text" name="alt_en" value="{{ $image->alt_en }}" placeholder="Alt text (EN)" aria-label="Alt text (EN)">
                                            <input type="text" name="caption_th" value="{{ $image->caption_th }}" placeholder="คำบรรยาย (TH)" aria-label="คำบรรยาย (TH)">
                                            <input type="text" name="caption_en" value="{{ $image->caption_en }}" placeholder="Caption (EN)" aria-label="Caption (EN)">
                                            <input type="number" name="sort_order" value="{{ $image->sort_order }}" min="0" aria-label="ลำดับ" placeholder="ลำดับ">
                                        </div>
                                        <div class="gallery-item__actions">
                                            <button class="btn btn--secondary" type="submit">บันทึกภาพ</button>
                                        </div>
                                    </form>
                                    <form method="POST" action="{{ route('admin.projects.images.destroy', [$project, $image]) }}" onsubmit="return confirm('ลบภาพนี้?')" style="margin-top:8px">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-link" type="submit">ลบภาพ</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        <form class="danger-zone" method="POST" action="{{ route('admin.projects.destroy', $project) }}" onsubmit="return confirm('ลบผลงานนี้และภาพทั้งหมด?')">
            @csrf
            @method('DELETE')
            <p>ลบผลงานนี้และภาพทั้งหมดออกจากระบบ</p>
            <button class="btn btn--danger" type="submit">ลบผลงาน</button>
        </form>
    @endif
@endsection
