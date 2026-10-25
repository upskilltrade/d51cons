@extends('admin.layout', ['title' => 'บริการ'])

@section('content')
    <div class="row"><h1>บริการ</h1><a class="btn" href="{{ route('admin.services.create') }}">เพิ่มบริการ</a></div>
    <table>
        <thead><tr><th>ลำดับ</th><th>ชื่อ</th><th>slug</th><th>เผยแพร่</th></tr></thead>
        <tbody>
            @forelse ($services as $service)
                <tr>
                    <td>{{ $service->sort_order }}</td>
                    <td><a href="{{ route('admin.services.edit', $service) }}">{{ $service->title_th }}</a></td>
                    <td>{{ $service->slug }}</td>
                    <td>{{ $service->is_published ? 'เผยแพร่' : 'ฉบับร่าง' }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">ยังไม่มีบริการ</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
