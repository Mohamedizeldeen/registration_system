<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-in for {{ $attendee->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-qrcode text-blue-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Event Check-in</h1>
            <p class="text-gray-600 mt-2">QR Code Verification</p>
        </div>

        <!-- Attendee Information -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="font-semibold text-gray-900 mb-3">Attendee Details</h2>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-600">Name:</span>
                    <span class="font-medium">{{ $attendee->full_name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $attendee->email }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Event:</span>
                    <span class="font-medium">{{ $attendee->event->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Ticket:</span>
                    <span class="font-medium">{{ $attendee->ticket->name ?? 'Standard' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Zone:</span>
                    <span class="font-medium">{{ $attendee->ticket->eventZone->name ?? 'General' }}</span>
                </div>
            </div>
        </div>

        <!-- Check-in Status -->
        <div id="checkin-status" class="mb-6">
            @if($attendee->checked_in)
                <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span class="font-medium">Already Checked In</span>
                    </div>
                    <p class="text-sm mt-1">
                        Checked in on {{ $attendee->checked_in_at->format('M j, Y') }} at {{ $attendee->checked_in_at->format('g:i A') }}
                    </p>
                </div>
            @else
                <div class="bg-yellow-100 border border-yellow-300 text-yellow-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span class="font-medium">Not Checked In</span>
                    </div>
                    <p class="text-sm mt-1">Ready to check in to the event</p>
                </div>
            @endif
        </div>

        <!-- Check-in Action -->
        <div class="space-y-4">
            @if(!$attendee->checked_in)
                <button 
                    onclick="processCheckin()" 
                    id="checkin-btn"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center"
                >
                    <i class="fas fa-check mr-2"></i>
                    Check In Now
                </button>
            @else
                <div class="text-center text-gray-500">
                    <i class="fas fa-check-circle text-3xl mb-2"></i>
                    <p>Check-in Complete</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="text-center mt-6 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                Event Management System<br>
                Scan QR code to verify attendance
            </p>
        </div>
    </div>

    <script>
        // Set up CSRF token for AJAX requests
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function processCheckin() {
            const btn = document.getElementById('checkin-btn');
            const statusDiv = document.getElementById('checkin-status');
            
            // Disable button and show loading
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            
            try {
                const response = await fetch(`{{ route('qr.checkin.process', $attendee->id) }}?{{ request()->getQueryString() }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                
                const data = await response.json();
                
                if (data.status === 'success') {
                    // Update status display
                    statusDiv.innerHTML = `
                        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                <span class="font-medium">Successfully Checked In!</span>
                            </div>
                            <p class="text-sm mt-1">
                                Checked in at ${data.checked_in_at}
                            </p>
                        </div>
                    `;
                    
                    // Replace button with success message
                    btn.parentElement.innerHTML = `
                        <div class="text-center text-green-600">
                            <i class="fas fa-check-circle text-3xl mb-2"></i>
                            <p class="font-medium">Check-in Complete!</p>
                        </div>
                    `;
                } else {
                    // Show info message (already checked in)
                    statusDiv.innerHTML = `
                        <div class="bg-blue-100 border border-blue-300 text-blue-700 px-4 py-3 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span class="font-medium">${data.message}</span>
                            </div>
                        </div>
                    `;
                    
                    btn.style.display = 'none';
                }
            } catch (error) {
                console.error('Error:', error);
                statusDiv.innerHTML = `
                    <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <span class="font-medium">Check-in Failed</span>
                        </div>
                        <p class="text-sm mt-1">Please try again or contact event staff</p>
                    </div>
                `;
                
                // Re-enable button
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check mr-2"></i>Check In Now';
            }
        }
    </script>
</body>
</html>
