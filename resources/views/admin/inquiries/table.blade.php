<table>
    <thead><tr><th>#</th><th>ชื่อ</th><th>ประเภทงาน</th><th>สถานะ</th><th>อีเมลแจ้งทีม</th><th>วันที่</th></tr></thead>
    <tbody>
        @forelse ($inquiries as $inquiry)
            <tr>
                <td><a href="{{ route('admin.inquiries.show', $inquiry) }}">{{ $inquiry->id }}</a></td>
                <td><a href="{{ route('admin.inquiries.show', $inquiry) }}">{{ $inquiry->name }}</a></td>
                <td>{{ $inquiry->service_type }}</td>
                <td>{{ __('admin.inquiry.status.'.$inquiry->status->value) }}</td>
                <td>{{ __('admin.inquiry.mail_status.'.$inquiry->mail_status->value) }}</td>
                <td>{{ $inquiry->created_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr><td colspan="6" class="muted">ยังไม่มีข้อความ</td></tr>
        @endforelse
    </tbody>
</table>
