<!-- filepath: c:\laragon\www\MFW-register\resources\views\tickets\print-a6.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Tickets</title>
    <style>
        @page {
            size: A6 portrait;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9px;
            line-height: 1.2;
            background: #f3f4f6;
        }
        .tickets-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            width: 100vw;
        }
        .ticket {
            width: 100%;
            height: 100%;
            padding: 6mm;
            
            box-sizing: border-box;
            border: 2px solid #333;
            background: #fff;
            color: #111;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            box-shadow: 0 2px 12px 0 rgba(0,0,0,0.08);
        }
        .ticket-header {
            text-align: center;
            margin-bottom: 6mm;
            border-bottom: 2px solid #242424;
            padding-bottom: 3mm;
            width: 100%;
        }
        .event-title {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 2mm;
            text-transform: uppercase;
            line-height: 1.1;
        }
        .company-name {
            font-size: 10px;
            margin-bottom: 0;
        }
        .attendee-section {
            background: rgba(0,0,0,0.03);
            padding: 4mm;
            margin: 4mm 0;
            border: 1px solid #e5e7eb;
            text-align: center;
            width: 100%;
            border-radius: 4px;
        }
        .attendee-name {
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 1mm;
            line-height: 1.1;
        }
        .attendee-email {
            font-size: 8px;
            word-break: break-all;
        }
        .ticket-info {
            margin: 4mm 0;
            width: 100%;
        }
        .info-row {
            margin-bottom: 1.5mm;
            display: flex;
            justify-content: space-between;
            width: 100%;
        }
        .info-label {
            font-weight: bold;
            font-size: 8px;
            opacity: 0.8;
        }
        .info-value {
            font-size: 8px;
            text-align: right;
            word-wrap: break-word;
            opacity: 0.95;
        }
        .qr-section {
            text-align: center;
            margin: 4mm 0 2mm 0;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .qr-code {
            width: 25mm;
            height: 25mm;
            background: white;
            color: black;
            padding: 2mm;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border-radius: 4px;
        }
        .qr-code svg {
            width: 100%;
            height: 100%;
            display: block;
        }
        .qr-placeholder {
            width: 100%;
            height: 100%;
            background: #f0f0f0;
            border: 1px dashed #666;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #666;
            font-size: 7px;
        }
        .qr-text {
            font-size: 7px;
            margin-top: 2mm;
            text-align: center;
        }
        .ticket-footer {
            text-align: center;
            font-size: 7px;
            border-top: 1px solid #e5e7eb;
            padding-top: 2mm;
            margin-top: 4mm;
            width: 100%;
        }
        .zone-info {
            background: #fef9c3;
            border: 1px solid #fde047;
            padding: 2mm;
            margin: 3mm 0;
            text-align: center;
            border-radius: 4px;
            width: 100%;
        }
        .zone-title {
            font-size: 7px;
            font-weight: bold;
            color: #b45309;
            text-transform: uppercase;
            margin-bottom: 1mm;
        }
        .zone-name {
            font-size: 9px;
            font-weight: bold;
            color: #92400e;
        }
        @print {
            body, .tickets-container {
                margin: 0;
                padding: 0;
                box-shadow: none;
                background: white;
            }
            .ticket {
                box-shadow: none;
                border: 1px solid #333;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    @if(count($attendees) == 0)
    <div style="padding: 20px; text-align: center; font-size: 16px; color: black;">
        <h3>Debug Information:</h3>
        <p>No attendees data received!</p>
        <p>Attendees count: {{ count($attendees ?? []) }}</p>
        <p>Current time: {{ date('Y-m-d H:i:s') }}</p>
    </div>
    @else
        <div class="tickets-container">
            @foreach($attendees as $attendee)
            <div class="ticket">
                <!-- Header -->
                <div class="ticket-header">
                    <div class="event-title">{{ $attendee->event->name ?? 'Event Ticket' }}</div>
                    <div class="company-name">{{ $attendee->event->company->name ?? 'Event Company' }}</div>
                </div>
                <!-- Attendee Information -->
                <div class="attendee-section">
                    <div class="attendee-name">
                        {{ $attendee->full_name ?? ($attendee->first_name . ' ' . $attendee->last_name) }}
                    </div>
                    <div class="attendee-email">{{ $attendee->email ?? 'No email' }}</div>
                </div>
                <!-- Zone Access Information -->
                @if($attendee->ticket && $attendee->ticket->eventZone)
                <div class="zone-info">
                    <div class="zone-title">Zone Access</div>
                    <div class="zone-name">{{ $attendee->ticket->eventZone->name }}</div>
                </div>
                @endif
                <!-- Event Information -->
                <div class="ticket-info">
                    @if($attendee->event && $attendee->event->event_date)
                    <div class="info-row">
                        <span class="info-label">Date:</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($attendee->event->event_date)->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($attendee->event && $attendee->event->start_time)
                    <div class="info-row">
                        <span class="info-label">Time:</span>
                        <span class="info-value">{{ $attendee->event->start_time }}</span>
                    </div>
                    @endif
                    @if($attendee->event && $attendee->event->location)
                    <div class="info-row">
                        <span class="info-label">Venue:</span>
                        <span class="info-value">{{ Str::limit($attendee->event->location, 20) }}</span>
                    </div>
                    @endif
                    @if($attendee->ticket)
                    <div class="info-row">
                        <span class="info-label">Ticket:</span>
                        <span class="info-value">{{ $attendee->ticket->name ?? 'Standard' }}</span>
                    </div>
                    @endif
                </div>
                <!-- QR Code Section -->
                <div class="qr-section">
                    <div class="qr-code">
                        @if($attendee->qr_code)
                            {!! $attendee->qr_code !!}
                        @elseif(isset($attendee->qr_code_data))
                            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(70)->backgroundColor(255,255,255)->color(0,0,0)->generate($attendee->qr_code_data) !!}
                        @else
                            <div class="qr-placeholder">
                                QR Code<br>
                                #{{ $attendee->id }}
                            </div>
                        @endif
                    </div>
                    <div class="qr-text">
                        Scan to Check-In • ID: {{ $attendee->id }}
                    </div>
                </div>
                <!-- Footer -->
                <div class="ticket-footer">
                    <div style="margin-top: 1mm;">{{ date('M d, Y H:i') }}</div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</body>
</html>