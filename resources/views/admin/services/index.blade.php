@extends('admin.layout', ['title' => 'บริการ'])

@section('content')
    <div class="page-head">
        <div>
            <h1>บริการ</h1>
            <p>บริการที่ “เผยแพร่” จะแสดงบนหน้าแรกและหน้าบริการ</p>
        </div>
        <a class="btn" href="{{ route('admin.services.create') }}"><x-icon name="plus" /> เพิ่มบริการ</a>
    </div>
    <section class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>ลำดับ</th><th></th><th>ชื่อ</th><th>slug</th><th>สถานะ</th></tr></thead>
                <tbody>
                    @forelse ($services as $service)
                        <tr>
                            <td class="cell-num">{{ $service->sort_order }}</td>
                            <td class="cell-thumb">@if ($service->imageUrl())<img src="{{ $service->imageUrl() }}" alt="">@else<span></span>@endif</td>
                            <td><a href="{{ route('admin.services.edit', $service) }}">{{ $service->title_th }}</a><div class="muted" style="font-size:13px">{{ $service->title_en }}</div></td>
                            <td class="cell-num">{{ $service->slug }}</td>
                            <td>
                                @if ($service->is_published)
                                    <span class="badge badge--ok">เผยแพร่</span>
                                @else
                                    <span class="badge">ฉบับร่าง</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="empty">ยังไม่มีบริการ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
