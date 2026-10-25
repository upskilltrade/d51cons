@extends('admin.layout', ['title' => 'ข้อความติดต่อ'])

@section('content')
    <div class="row">
        <h1>ข้อความติดต่อ</h1>
        <nav>
            <a href="{{ route('admin.inquiries.index') }}">ทั้งหมด</a>
            @foreach ($statuses as $item)
                · <a href="{{ route('admin.inquiries.index', ['status' => $item->value]) }}" @if ($status === $item) aria-current="true" @endif>{{ __('admin.inquiry.status.'.$item->value) }}</a>
            @endforeach
        </nav>
    </div>
    @include('admin.inquiries.table')
    {{ $inquiries->links() }}
@endsection
