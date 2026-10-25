@extends('admin.layout', ['title' => 'ข้อความ #'.$inquiry->id])

@section('content')
    <h1>ข้อความ #{{ $inquiry->id }}</h1>
    <table>
        <tr><th>ชื่อ</th><td>{{ $inquiry->name }}</td></tr>
        <tr><th>อีเมล</th><td><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td></tr>
        <tr><th>โทรศัพท์</th><td>{{ $inquiry->phone }}</td></tr>
        <tr><th>ประเภทงาน</th><td>{{ $inquiry->service_type }}</td></tr>
        <tr><th>สถานที่</th><td>{{ $inquiry->location }}</td></tr>
        <tr><th>งบประมาณ</th><td>{{ $inquiry->budget_range ? __('site.budget_ranges.'.$inquiry->budget_range) : '' }}</td></tr>
        <tr><th>ภาษา</th><td>{{ $inquiry->locale }}</td></tr>
        <tr><th>ยอมรับนโยบาย</th><td>{{ $inquiry->privacy_acknowledged_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}</td></tr>
        <tr><th>ได้รับเมื่อ</th><td>{{ $inquiry->created_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}</td></tr>
        <tr><th>รายละเอียด</th><td style="white-space:pre-line">{{ $inquiry->message }}</td></tr>
        <tr>
            <th>อีเมลแจ้งทีม</th>
            <td>
                {{ __('admin.inquiry.mail_status.'.$inquiry->mail_status->value) }}
                @if ($inquiry->notified_at) <span class="muted">({{ $inquiry->notified_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }})</span> @endif
                @if ($inquiry->canRetryMail())
                    <form method="POST" action="{{ route('admin.inquiries.resend', $inquiry) }}" style="display:inline">
                        @csrf
                        <button class="btn-link" type="submit">ส่งอีกครั้ง</button>
                    </form>
                @endif
            </td>
        </tr>
    </table>

    <form method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}">
        @csrf
        @method('PATCH')
        <label for="status">สถานะ</label>
        <select id="status" name="status">
            @foreach ($statuses as $item)
                <option value="{{ $item->value }}" @selected($inquiry->status === $item)>{{ __('admin.inquiry.status.'.$item->value) }}</option>
            @endforeach
        </select>
        <p><button class="btn" type="submit">บันทึกสถานะ</button></p>
    </form>
@endsection
