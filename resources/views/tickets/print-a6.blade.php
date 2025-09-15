<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Tickets</title>
    <style>
        @page {
            margin: 0;
            size: A6 portrait;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            line-height: 1.2;
        }
        
        .ticket {
            width: 105mm;
            height: 148mm;
            padding: 8mm;
            box-sizing: border-box;
            border: 1px solid #ddd;
            page-break-after: always;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .ticket:last-child {
            page-break-after: avoid;
        }
        
        .ticket-header {
            text-align: center;
            margin-bottom: 8mm;
            border-bottom: 2px solid rgba(255,255,255,0.3);
            padding-bottom: 4mm;
        }
        
        .event-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 2mm;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .company-name {
            font-size: 10px;
            opacity: 0.9;
            margin-bottom: 2mm;
        }
        
        .ticket-info {
            margin-bottom: 6mm;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2mm;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            font-size: 9px;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        .info-value {
            font-size: 10px;
            text-align: right;
            max-width: 60%;
            word-wrap: break-word;
        }
        
        .attendee-section {
            background: rgba(255,255,255,0.1);
            padding: 4mm;
            margin: 4mm -2mm;
            border-radius: 4px;
            backdrop-filter: blur(5px);
        }
        
        .attendee-name {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 2mm;
            text-align: center;
        }
        
        .attendee-email {
            font-size: 9px;
            text-align: center;
            opacity: 0.9;
            word-wrap: break-word;
        }
        
        .qr-section {
            text-align: center;
            margin-top: 6mm;
        }
        
        .qr-code {
            width: 25mm;
            height: 25mm;
            margin: 0 auto;
            border-radius: 4px;
            background: white;
            padding: 2mm;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .qr-code img {
            max-width: 100%;
            max-height: 100%;
        }
        
        .ticket-footer {
            position: absolute;
            bottom: 4mm;
            left: 8mm;
            right: 8mm;
            text-align: center;
            font-size: 8px;
            opacity: 0.7;
            border-top: 1px solid rgba(255,255,255,0.3);
            padding-top: 2mm;
        }
        
        .price-badge {
            background: rgba(255,255,255,0.2);
            padding: 1mm 3mm;
            border-radius: 10px;
            font-weight: bold;
            font-size: 9px;
        }
        
        .status-badge {
            background: #10b981;
            padding: 1mm 2mm;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .status-badge.pending {
            background: #f59e0b;
        }
        
        /* Decorative elements */
        .ticket::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
        }
        
        .divider {
            border-bottom: 1px dashed rgba(255,255,255,0.3);
            margin: 3mm 0;
            padding-bottom: 2mm;
        }
    </style>
</head>
<body class="flex justify-center items-center">
    <div class="w-[70%] h-[70%]">
    @if(count($attendees) == 0)
    <div style="padding: 20px; text-align: center; font-size: 16px;">
        No attendees data received!
    </div>
    @endif
    
    @foreach($attendees as $attendee)
    <div class="ticket">
        <!-- Header -->
        <div class="ticket-header">
            <div class="event-title">{{ $attendee->event->name ?? 'Event Ticket' }}</div>
            <div class="company-name">{{ $attendee->event->company->name ?? 'Event Company' }}</div>
        </div>
        
        <!-- Simple Attendee Information -->
        <div class="attendee-section">
            <div class="attendee-name">{{ $attendee->full_name ?? $attendee->first_name . ' ' . $attendee->last_name }}</div>
            <div class="attendee-email">{{ $attendee->email ?? 'No email' }}</div>
        </div>
        
        <!-- Basic Event Information -->
        <div class="ticket-info">
            @if($attendee->event && $attendee->event->event_date)
            <div class="info-row">
                <span class="info-label">Date</span>
                <span class="info-value">{{ \Carbon\Carbon::parse($attendee->event->event_date)->format('M d, Y') }}</span>
            </div>
            @endif
            
            @if($attendee->event && $attendee->event->location)
            <div class="info-row">
                <span class="info-label">Venue</span>
                <span class="info-value">{{ $attendee->event->location }}</span>
            </div>
            @endif
            
            @if($attendee->ticket)
            <div class="info-row">
                <span class="info-label">Ticket</span>
                <span class="info-value">{{ $attendee->ticket->name ?? 'Standard' }}</span>
            </div>
            @endif
        </div>
        
        <!-- QR Code Section -->
        <div class="qr-section">
            <div class="qr-code">
                @if($attendee->qr_code)
                    {!! $attendee->qr_code !!}
                @else
                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->backgroundColor(255,255,255)->color(0,0,0)->generate($attendee->simple_qr_data) !!}
                @endif
            </div>
            <div style="font-size: 8px; margin-top: 2mm; opacity: 0.8;">
                Scan to Check-In #{{ $attendee->id }}
            </div>
        </div>
        
        <!-- Footer -->
        <div class="ticket-footer">
            <div>Ticket ID: #{{ $attendee->id }}</div>
            <div style="margin-top: 1mm;">{{ config('app.name', 'Event Management') }}</div>
        </div>
    </div>
    @endforeach
    </div>
</body>
</html>
