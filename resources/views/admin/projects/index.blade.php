@extends('admin.layout', ['title' => 'ผลงาน'])

@section('content')
    <div class="row"><h1>ผลงาน</h1><a class="btn" href="{{ route('admin.projects.create') }}">เพิ่มผลงาน</a></div>
    <table>
        <thead><tr><th>ลำดับ</th><th>ชื่อ</th><th>ประเภท</th><th>สถานะงาน</th><th>ชนิด</th><th>เผยแพร่</th></tr></thead>
        <tbody>
            @forelse ($projects as $project)
                <tr>
                    <td>{{ $project->sort_order }}</td>
                    <td><a href="{{ route('admin.projects.edit', $project) }}">{{ $project->title_th }}</a></td>
                    <td>{{ $project->service?->title_th }}</td>
                    <td>{{ $project->status->label() }}</td>
                    <td>{{ $project->is_concept ? 'ภาพแนวคิด' : 'ผลงานจริง' }}</td>
                    <td>{{ $project->isPublished() ? 'เผยแพร่' : ($project->published_at ? 'ตั้งเวลา' : 'ฉบับร่าง') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="muted">ยังไม่มีผลงาน</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $projects->links() }}
@endsection
