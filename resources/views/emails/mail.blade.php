<!DOCTYPE html>
<html>
<head>
    <title>{{ $title ?? 'Notification' }}</title>
</head>
<body>
    <h3>{{ $title ?? 'Hello!' }}</h3>
    
    {{-- This allows you to pass plain text or HTML strings --}}
    <p>{!! $body !!}</p>

    @if(isset($pi))
        <br><strong>Reference No:</strong> {{ $pi->reference_no }}
        <br><strong>PI Type:</strong> {{ $pi->type }}
        <br><strong>Customer:</strong> {{ $pi->customer?->customer_name ?? $pi->new_customer_name }}
        <br><strong>Requestor:</strong> {{ $pi->creator->name }}
        <br><strong>Submitted At:</strong> {{ $pi->created_at->format('Y-m-d H:i') }}
        @if($pi->assign_user)
            <br><strong>Cost Estimator:</strong> {{ $pi?->assign_user?->name }}
        @endif
    @endif

    @if(isset($bom_creation))
        <br><strong>Reference No:</strong> {{ $bom_creation->reference_no }}
        <br><strong>Customer:</strong> {{ $bom_creation->customer->customer_name }}
        <br><strong>Requestor:</strong> {{ $bom_creation->creator->name }}
        <br><strong>Submitted At:</strong> {{ $bom_creation->created_at->format('Y-m-d H:i') }}
        @if($bom_creation->assign_user)
            <br><strong>Cost Estimator:</strong> {{ $bom_creation?->assign_user?->name }}
        @endif
    @endif

    @if(isset($remarks))
        <br><br><strong>Remarks:</strong> {{ $remarks }}
    @endif

    @if(isset($actionUrl))
        <p>
            <a href="{{ $actionUrl }}" style="background: #3490dc; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
                {{ $actionText ?? 'Click Here' }}
            </a>
        </p>
    @endif

    <br>
    <br>
    <p>Thank you,<br>{{ config('app.name') }}</p>
</body>
</html>