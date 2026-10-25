@php
    $statusTone = ['new' => 'brick', 'contacted' => 'info', 'closed' => 'ok'];
    $mailTone = ['pending' => 'warn', 'sending' => 'warn', 'sent' => 'ok', 'failed' => 'danger'];
@endphp
<div class="table-wrap">
    <table>
        <thead><tr><th>#</th><th>ชื่อ</th><th>ประเภทงาน</th><th>สถานะ</th><th>อีเมลแจ้งทีม</th><th>วันที่</th></tr></thead>
        <tbody>
            @forelse ($inquiries as $inquiry)
                <tr>
                    <td class="cell-num"><a href="{{ route('admin.inquiries.show', $inquiry) }}">{{ $inquiry->id }}</a></td>
                    <td><a href="{{ route('admin.inquiries.show', $inquiry) }}">{{ $inquiry->name }}</a><div class="muted" style="font-size:13px">{{ $inquiry->email }}</div></td>
                    <td>{{ $inquiry->service_type }}</td>
                    <td><span class="badge badge--{{ $statusTone[$inquiry->status->value] ?? '' }}">{{ __('admin.inquiry.status.'.$inquiry->status->value) }}</span></td>
                    <td><span class="badge badge--{{ $mailTone[$inquiry->mail_status->value] ?? '' }}">{{ __('admin.inquiry.mail_status.'.$inquiry->mail_status->value) }}</span></td>
                    <td class="cell-num">{{ $inquiry->created_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="empty">ยังไม่มีข้อความ</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
