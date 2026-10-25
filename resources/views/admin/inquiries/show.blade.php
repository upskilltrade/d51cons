@extends('admin.layout', ['title' => 'ข้อความ #'.$inquiry->id])

@php
    $mailTone = ['pending' => 'warn', 'sending' => 'warn', 'sent' => 'ok', 'failed' => 'danger'];
    $service = $inquiry->service_type ? \App\Models\Service::query()->where('slug', $inquiry->service_type)->value('title_th') : null;
@endphp

@section('content')
    <a class="crumb" href="{{ route('admin.inquiries.index') }}"><x-icon name="arrow-left" /> ข้อความติดต่อ</a>
    <div class="page-head">
        <div>
            <h1>{{ $inquiry->name }}</h1>
            <p>ข้อความ #{{ $inquiry->id }} · ได้รับเมื่อ {{ $inquiry->created_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}</p>
        </div>
        <a class="btn" href="mailto:{{ $inquiry->email }}"><x-icon name="mail" /> ตอบกลับทางอีเมล</a>
    </div>

    <div class="detail-grid">
        <div class="stack">
            <section class="card">
                <div class="card__head"><h2>รายละเอียดโครงการ</h2></div>
                <div class="message-body">{{ $inquiry->message }}</div>
            </section>
            <section class="card">
                <div class="card__head"><h2>ข้อมูลผู้ติดต่อ</h2></div>
                <table class="kv">
                    <tr><th>อีเมล</th><td><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td></tr>
                    <tr><th>โทรศัพท์</th><td>@if ($inquiry->phone)<a href="tel:{{ preg_replace('/[^\d+]/', '', $inquiry->phone) }}">{{ $inquiry->phone }}</a>@else<span class="muted">-</span>@endif</td></tr>
                    <tr><th>ประเภทงาน</th><td>{{ $service ?? $inquiry->service_type ?? '-' }}</td></tr>
                    <tr><th>สถานที่</th><td>{{ $inquiry->location ?: '-' }}</td></tr>
                    <tr><th>งบประมาณ</th><td>{{ $inquiry->budget_range ? __('site.budget_ranges.'.$inquiry->budget_range) : '-' }}</td></tr>
                    <tr><th>ภาษา</th><td>{{ strtoupper($inquiry->locale) }}</td></tr>
                    <tr><th>ยอมรับนโยบาย</th><td>{{ $inquiry->privacy_acknowledged_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}</td></tr>
                </table>
            </section>
        </div>

        <div class="stack">
            <form class="card" method="POST" action="{{ route('admin.inquiries.update', $inquiry) }}">
                @csrf
                @method('PATCH')
                <div class="card__head"><h2>สถานะการติดตาม</h2></div>
                <div class="card__body">
                    <div class="field">
                        <label for="status">สถานะ</label>
                        <select id="status" name="status">
                            @foreach ($statuses as $item)
                                <option value="{{ $item->value }}" @selected($inquiry->status === $item)>{{ __('admin.inquiry.status.'.$item->value) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn btn--block" type="submit" style="margin-top:16px">บันทึกสถานะ</button>
                </div>
            </form>

            <section class="card">
                <div class="card__head"><h2>อีเมลแจ้งทีม</h2></div>
                <div class="card__body">
                    <span class="badge badge--{{ $mailTone[$inquiry->mail_status->value] ?? '' }}">{{ __('admin.inquiry.mail_status.'.$inquiry->mail_status->value) }}</span>
                    @if ($inquiry->notified_at)
                        <p class="muted" style="margin:8px 0 0;font-size:14px">{{ $inquiry->notified_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}</p>
                    @endif
                    @if ($inquiry->canRetryMail())
                        <form method="POST" action="{{ route('admin.inquiries.resend', $inquiry) }}" style="margin-top:16px">
                            @csrf
                            <button class="btn btn--secondary btn--block" type="submit">ส่งอีกครั้ง</button>
                        </form>
                    @endif
                </div>
            </section>
        </div>
    </div>
@endsection
