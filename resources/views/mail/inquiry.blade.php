<!DOCTYPE html>
<html lang="th">
<body style="font-family: sans-serif; color: #25282A;">
    <h1 style="font-size: 18px;">New website inquiry #{{ $inquiry->id }}</h1>
    <table cellpadding="6">
        @foreach (['name', 'email', 'phone', 'service_type', 'location', 'budget_range', 'locale'] as $field)
            @if (filled($inquiry->{$field}))
                <tr><th align="left">{{ $field }}</th><td>{{ $inquiry->{$field} }}</td></tr>
            @endif
        @endforeach
        <tr><th align="left">received</th><td>{{ $inquiry->created_at->timezone('Asia/Bangkok')->format('Y-m-d H:i') }}</td></tr>
    </table>
    <p style="white-space: pre-line;">{{ $inquiry->message }}</p>
    <p><a href="{{ route('admin.inquiries.show', $inquiry) }}">Open in admin</a></p>
</body>
</html>
