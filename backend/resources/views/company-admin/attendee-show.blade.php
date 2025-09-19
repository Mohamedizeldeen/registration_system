@extends('layouts.app')

@section('title', 'Attendee Details')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('company-admin.attendees') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $attendee->full_name }}</h1>
                        <p class="text-gray-600">Attendee Details - {{ $attendee->event->name }}</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    @if($attendee->qr_code)
                        <button onclick="copyRegistrationLink('{{ route('public.confirmation', [$attendee->event->id, $attendee->ticket->id, $attendee->id]) }}')" 
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200 flex items-center">
                            <i class="fas fa-share-alt mr-2"></i>
                            Share Ticket
                        </button>
                    @endif
                    <a href="{{ route('company-admin.quick-print', $attendee->id) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-print mr-2"></i>
                        Print Ticket
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Attendee Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 text-blue-600"></i>
                        Personal Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Full Name</label>
                            <p class="text-gray-900 font-medium">{{ $attendee->full_name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <p class="text-gray-900">{{ $attendee->email }}</p>
                        </div>
                        @if($attendee->phone)
                            <div>
                                <label class="text-sm font-medium text-gray-700">Phone</label>
                                <p class="text-gray-900">{{ $attendee->phone }}</p>
                            </div>
                        @endif
                        @if($attendee->company)
                            <div>
                                <label class="text-sm font-medium text-gray-700">Company</label>
                                <p class="text-gray-900">{{ $attendee->company }}</p>
                            </div>
                        @endif
                        @if($attendee->position)
                            <div>
                                <label class="text-sm font-medium text-gray-700">Position</label>
                                <p class="text-gray-900">{{ $attendee->position }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-700">Registration Date</label>
                            <p class="text-gray-900">{{ $attendee->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Event & Ticket Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-ticket-alt mr-2 text-green-600"></i>
                        Event & Ticket Information
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-700">Event</label>
                            <p class="text-gray-900 font-medium">{{ $attendee->event->name }}</p>
                            <p class="text-sm text-gray-600">{{ $attendee->event->company->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Event Date</label>
                            <p class="text-gray-900">{{ $attendee->event->event_date->format('l, F j, Y') }}</p>
                        </div>
                        @if($attendee->event->location)
                            <div>
                                <label class="text-sm font-medium text-gray-700">Location</label>
                                <p class="text-gray-900">{{ $attendee->event->location }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-700">Ticket Type</label>
                            <p class="text-gray-900 font-medium">{{ $attendee->ticket->name }}</p>
                            @if($attendee->ticket->info)
                                <p class="text-sm text-gray-600">{{ $attendee->ticket->info }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Zone Access</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $attendee->ticket->eventZone->name }}
                            </span>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700">Ticket Price</label>
                            <p class="text-gray-900 font-medium">
                                {{ $attendee->ticket->currency->symbol ?? '$' }}{{ number_format($attendee->ticket->price, 2) }}
                                <span class="text-sm text-gray-600">({{ $attendee->ticket->currency->code ?? 'USD' }})</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                @if($attendee->payments->count() > 0)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-credit-card mr-2 text-purple-600"></i>
                            Payment Information
                        </h2>
                        <div class="space-y-4">
                            @foreach($attendee->payments as $payment)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                    @if($payment->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                                <span class="text-sm text-gray-500">{{ $payment->created_at->format('M j, Y g:i A') }}</span>
                                            </div>
                                            @if($payment->payment_method)
                                                <p class="text-sm text-gray-600 mt-1">Payment Method: {{ ucfirst($payment->payment_method) }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-gray-900">${{ number_format($payment->amount, 2) }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- QR Code & Actions -->
            <div class="space-y-6">
                <!-- Check-in Status -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-qrcode mr-2 text-indigo-600"></i>
                        Check-in Status
                    </h3>
                    <div class="text-center">
                        @if($attendee->checked_in)
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-check text-green-600 text-2xl"></i>
                            </div>
                            <p class="font-medium text-green-800">Checked In</p>
                            <p class="text-sm text-gray-600">{{ $attendee->checked_in_at ? $attendee->checked_in_at->format('M j, Y g:i A') : 'Date not available' }}</p>
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-clock text-gray-600 text-2xl"></i>
                            </div>
                            <p class="font-medium text-gray-800">Not Checked In</p>
                            <p class="text-sm text-gray-600">Waiting for event check-in</p>
                        @endif
                    </div>
                </div>

                <!-- QR Code -->
                @if($attendee->qr_code)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">QR Code Ticket</h3>
                        <div class="text-center">
                            <div class="inline-block bg-white p-4 rounded-lg border-2 border-gray-200">
                                {!! $attendee->qr_code !!}
                            </div>
                            <p class="text-sm text-gray-600 mt-3">Scan this QR code for event entry</p>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('company-admin.quick-print', $attendee->id) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                            <i class="fas fa-print mr-2"></i>
                            Print Ticket
                        </a>
                        @if($attendee->qr_code)
                            <button onclick="copyRegistrationLink('{{ route('public.confirmation', [$attendee->event->id, $attendee->ticket->id, $attendee->id]) }}')" 
                                    class="w-full flex items-center justify-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition duration-200">
                                <i class="fas fa-share-alt mr-2"></i>
                                Copy Ticket Link
                            </button>
                        @endif
                        <a href="{{ route('qr.checkin', $attendee->id) }}" 
                           class="w-full flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition duration-200">
                            <i class="fas fa-qrcode mr-2"></i>
                            Manual Check-in
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Copy Registration Link Script -->
    <script>
        function copyRegistrationLink(url) {
            // Create a temporary textarea element
            const tempTextarea = document.createElement('textarea');
            tempTextarea.value = url;
            document.body.appendChild(tempTextarea);
            
            // Select and copy the text
            tempTextarea.select();
            tempTextarea.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                
                // Show success message
                showCopyNotification('Ticket link copied to clipboard!', 'success');
            } catch (err) {
                console.error('Failed to copy: ', err);
                showCopyNotification('Failed to copy link. Please try again.', 'error');
            }
            
            // Remove the temporary element
            document.body.removeChild(tempTextarea);
        }
        
        function showCopyNotification(message, type) {
            // Remove existing notification if any
            const existingNotification = document.querySelector('.copy-notification');
            if (existingNotification) {
                existingNotification.remove();
            }
            
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `copy-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} mr-2"></i>
                    ${message}
                </div>
            `;
            
            // Add to page
            document.body.appendChild(notification);
            
            // Auto remove after 3 seconds
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    </script>
@endpush
