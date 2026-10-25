New website inquiry #{{ $inquiry->id }}

@foreach (['name', 'email', 'phone', 'service_type', 'location', 'budget_range', 'locale'] as $field)
@if (filled($inquiry->{$field}))
{{ $field }}: {!! $inquiry->{$field} !!}
@endif
@endforeach
received: {{ $inquiry->created_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}

{!! $inquiry->message !!}

{{ route('admin.inquiries.show', $inquiry) }}
