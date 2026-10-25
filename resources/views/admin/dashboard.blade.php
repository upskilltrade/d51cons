@extends('admin.layout', ['title' => 'ภาพรวม'])

@section('content')
    <div class="page-head">
        <div>
            <h1>ภาพรวม</h1>
            <p>สรุปสถานะเว็บไซต์ d51cons.com</p>
        </div>
        <a class="btn btn--secondary" href="{{ route('home', ['locale' => config('d51.default_locale')]) }}" target="_blank" rel="noopener"><x-icon name="external" /> ดูเว็บไซต์</a>
    </div>

    <div class="stats">
        <a class="card stat stat--accent" href="{{ route('admin.inquiries.index', ['status' => 'new']) }}">
            <span class="stat__label"><x-icon name="inbox" /> ข้อความติดต่อใหม่</span>
            <span class="stat__value">{{ $newInquiries }}</span>
        </a>
        <a @class(['card', 'stat', 'stat--alert' => $failedMail > 0]) href="{{ route('admin.inquiries.index') }}">
            <span class="stat__label"><x-icon name="alert" /> อีเมลแจ้งเตือนส่งไม่สำเร็จ</span>
            <span class="stat__value">{{ $failedMail }}</span>
        </a>
        <a class="card stat" href="{{ route('admin.services.index') }}">
            <span class="stat__label"><x-icon name="layers" /> บริการที่เผยแพร่</span>
            <span class="stat__value">{{ $publishedServices }}</span>
        </a>
        <a class="card stat" href="{{ route('admin.projects.index') }}">
            <span class="stat__label"><x-icon name="image" /> ผลงานจริงที่เผยแพร่</span>
            <span class="stat__value">{{ $publishedProjects }}</span>
        </a>
        <a class="card stat" href="{{ route('admin.projects.index') }}">
            <span class="stat__label"><x-icon name="image" /> ภาพแนวคิดที่เผยแพร่</span>
            <span class="stat__value">{{ $publishedConcepts }}</span>
        </a>
    </div>

    @if ($publishedServices === 0)
        <div class="flash flash--error" role="note">
            <x-icon name="alert" />
            <span>ยังไม่มีบริการที่เผยแพร่ เว็บไซต์จึงแสดงรายการบริการตั้งต้น 4 รายการแทน ตรวจข้อความแล้วกด “เผยแพร่” ได้ที่ <a href="{{ route('admin.services.index') }}">บริการ</a></span>
        </div>
    @endif

    <section class="card">
        <div class="card__head">
            <h2>ข้อความล่าสุด</h2>
            <a href="{{ route('admin.inquiries.index') }}">ดูทั้งหมด</a>
        </div>
        @include('admin.inquiries.table', ['inquiries' => $latestInquiries])
    </section>
@endsection
