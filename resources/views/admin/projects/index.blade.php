@extends('admin.layout', ['title' => 'ผลงาน'])

@section('content')
    <div class="page-head">
        <div>
            <h1>ผลงาน</h1>
            <p>ผลงานจริงและภาพแนวคิด ภาพแนวคิดจะติดป้าย “ภาพแนวคิดประกอบ” และแยกจากผลงานจริงเสมอ</p>
        </div>
        <a class="btn" href="{{ route('admin.projects.create') }}"><x-icon name="plus" /> เพิ่มผลงาน</a>
    </div>
    <section class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>ลำดับ</th><th></th><th>ชื่อ</th><th>ประเภท</th><th>สถานะงาน</th><th>ชนิด</th><th>เผยแพร่</th></tr></thead>
                <tbody>
                    @forelse ($projects as $project)
                        <tr>
                            <td class="cell-num">{{ $project->sort_order }}</td>
                            <td class="cell-thumb">@if ($project->coverUrl())<img src="{{ $project->coverUrl() }}" alt="">@else<span></span>@endif</td>
                            <td><a href="{{ route('admin.projects.edit', $project) }}">{{ $project->title_th }}</a></td>
                            <td>{{ $project->service?->title_th ?? '-' }}</td>
                            <td>{{ $project->status->label() }}</td>
                            <td>
                                @if ($project->is_concept)
                                    <span class="badge badge--warn">ภาพแนวคิด</span>
                                @else
                                    <span class="badge badge--info">ผลงานจริง</span>
                                @endif
                            </td>
                            <td>
                                @if ($project->isPublished())
                                    <span class="badge badge--ok">เผยแพร่</span>
                                @elseif ($project->published_at)
                                    <span class="badge badge--brick">ตั้งเวลา</span>
                                @else
                                    <span class="badge">ฉบับร่าง</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="empty">ยังไม่มีผลงาน</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($projects->hasPages())
            <div class="card__foot">{{ $projects->links('partials.pagination') }}</div>
        @endif
    </section>
@endsection
