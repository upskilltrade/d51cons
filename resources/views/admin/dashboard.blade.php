@extends('admin.layout', ['title' => 'ภาพรวม'])

@section('content')
    <h1>ภาพรวม</h1>
    <table>
        <tr><th>ข้อความติดต่อใหม่</th><td><a href="{{ route('admin.inquiries.index', ['status' => 'new']) }}">{{ $newInquiries }}</a></td></tr>
        <tr><th>อีเมลแจ้งเตือนส่งไม่สำเร็จ</th><td>{{ $failedMail }}</td></tr>
        <tr><th>บริการที่เผยแพร่</th><td>{{ $publishedServices }}</td></tr>
        <tr><th>ผลงานจริงที่เผยแพร่</th><td>{{ $publishedProjects }}</td></tr>
        <tr><th>ภาพแนวคิดที่เผยแพร่</th><td>{{ $publishedConcepts }}</td></tr>
    </table>

    <h2>ข้อความล่าสุด</h2>
    @include('admin.inquiries.table', ['inquiries' => $latestInquiries])
@endsection
