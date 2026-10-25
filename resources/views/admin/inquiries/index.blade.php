@extends('admin.layout', ['title' => 'ข้อความติดต่อ'])

@section('content')
    <div class="page-head">
        <div>
            <h1>ข้อความติดต่อ</h1>
            <p>ข้อความจากแบบฟอร์มติดต่อบนเว็บไซต์</p>
        </div>
        <nav class="tabs" aria-label="กรองตามสถานะ">
            <a href="{{ route('admin.inquiries.index') }}" @if (! $status) aria-current="true" @endif>ทั้งหมด</a>
            @foreach ($statuses as $item)
                <a href="{{ route('admin.inquiries.index', ['status' => $item->value]) }}" @if ($status === $item) aria-current="true" @endif>{{ __('admin.inquiry.status.'.$item->value) }}</a>
            @endforeach
        </nav>
    </div>
    <section class="card">
        @include('admin.inquiries.table')
        @if ($inquiries->hasPages())
            <div class="card__foot">{{ $inquiries->links('partials.pagination') }}</div>
        @endif
    </section>
@endsection
