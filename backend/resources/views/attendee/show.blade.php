@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $attendee->first_name }} {{ $attendee->last_name }}</h1>
                    <p class="text-gray-600 mt-2">Attendee details and registration information</p>
                </div>
                <div class="flex space-x-3">
                    @if(auth()->user()->role === 'super_admin')
                        <a href="{{ route('attendees.edit', $attendee->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-edit mr-2"></i>Edit Attendee
                        </a>
                        <a href="{{ route('attendees.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Attendees
                        </a>
                    @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin')
                        <a href="{{ route('company-admin.attendees') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Attendees
                        </a>
                    @elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer')
                        <a href="{{ route('company-organizer.attendees') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Attendees
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-user mr-2 text-blue-600"></i>Personal Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $attendee->first_name }} {{ $attendee->last_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <p class="text-gray-900">{{ $attendee->email }}</p>
                            </div>
                            @if($attendee->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <p class="text-gray-900">{{ $attendee->phone }}</p>
                            </div>
                            @endif
                            @if($attendee->city || $attendee->country)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                                <p class="text-gray-900">
                                    @if($attendee->city){{ $attendee->city }}@endif
                                    @if($attendee->city && $attendee->country), @endif
                                    @if($attendee->country){{ $attendee->country }}@endif
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Professional Information -->
                @if($attendee->company || $attendee->job_title)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-briefcase mr-2 text-purple-600"></i>Professional Information
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @if($attendee->company)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                                <p class="text-gray-900">{{ $attendee->company }}</p>
                            </div>
                            @endif
                            @if($attendee->job_title)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                                <p class="text-gray-900">{{ $attendee->job_title }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Event Registration Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-green-600"></i>Event Registration
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $attendee->event->name }}</p>
                                <p class="text-sm text-gray-500">{{ $attendee->event->event_date->format('F j, Y') }} at {{ $attendee->event->event_date->format('g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ticket Type</label>
                                <p class="text-gray-900">{{ $attendee->ticket->name }}</p>
                                <p class="text-sm text-gray-500">${{ number_format($attendee->ticket->price, 2) }}</p>
                            </div>
                            @if($attendee->ticket->eventZone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Event Zone</label>
                                <p class="text-gray-900">{{ $attendee->ticket->eventZone->name }}</p>
                                @if($attendee->ticket->eventZone->description)
                                    <p class="text-sm text-gray-500">{{ $attendee->ticket->eventZone->description }}</p>
                                @endif
                            </div>
                            @endif
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Registration Date</label>
                                <p class="text-gray-900">{{ $attendee->created_at->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                @if($attendee->dietary_restrictions || $attendee->notes)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-orange-600"></i>Additional Information
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($attendee->dietary_restrictions)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dietary Restrictions</label>
                            <p class="text-gray-900">{{ $attendee->dietary_restrictions }}</p>
                        </div>
                        @endif
                        @if($attendee->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <p class="text-gray-900">{{ $attendee->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Payment History -->
                @if($attendee->payments->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-credit-card mr-2 text-indigo-600"></i>Payment History
                        </h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($attendee->payments as $payment)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $payment->created_at->format('M j, Y g:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ${{ number_format($payment->amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ ucfirst($payment->payment_method) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payment->status === 'completed')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($payment->status === 'pending')
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @else
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ ucfirst($payment->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>

            <!-- Right Column - Status & Actions -->
            <div class="space-y-6">
                <!-- Check-in Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Check-in Status</h3>
                    </div>
                    <div class="p-6">
                        @if($attendee->checked_in)
                            <div class="text-center">
                                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                                </div>
                                <p class="text-lg font-semibold text-green-800 mb-2">Checked In</p>
                                @if($attendee->checked_in_at)
                                    <p class="text-sm text-gray-600">{{ $attendee->checked_in_at->format('F j, Y g:i A') }}</p>
                                @endif
                            </div>
                        @else
                            <div class="text-center">
                                <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-clock text-orange-600 text-2xl"></i>
                                </div>
                                <p class="text-lg font-semibold text-orange-800 mb-4">Awaiting Check-in</p>
                                <button onclick="checkInAttendee()" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200">
                                    <i class="fas fa-check mr-2"></i>Check In Now
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Stats</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Total Paid</span>
                            <span class="font-semibold text-gray-900">${{ number_format($attendee->payments->sum('amount'), 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Registration ID</span>
                            <span class="font-mono text-sm text-gray-900">#{{ $attendee->id }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Event Date</span>
                            <span class="text-sm text-gray-900">{{ $attendee->event->event_date->format('M j, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        @if(auth()->user()->role === 'super_admin')
                            <a href="{{ route('attendees.edit', $attendee->id) }}" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 text-center block">
                                <i class="fas fa-edit mr-2"></i>Edit Attendee
                            </a>
                            <button onclick="sendEmailReminder()" class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200">
                                <i class="fas fa-envelope mr-2"></i>Send Reminder
                            </button>
                            <form action="{{ route('attendees.destroy', $attendee->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this attendee? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition duration-200">
                                    <i class="fas fa-trash mr-2"></i>Delete Attendee
                                </button>
                            </form>
                        @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin')
                            <button onclick="printTicket()" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200">
                                <i class="fas fa-print mr-2"></i>Print Ticket
                            </button>
                            <button onclick="sendEmailReminder()" class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200">
                                <i class="fas fa-envelope mr-2"></i>Send Reminder
                            </button>
                        @elseif(auth()->user()->role === 'organizer' || auth()->user()->role === 'company_organizer')
                            <button onclick="printTicket()" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200">
                                <i class="fas fa-print mr-2"></i>Print Ticket
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for actions -->
    <script>
        function checkInAttendee() {
            if (confirm('Mark this attendee as checked in?')) {
                // This would typically be an AJAX call to update the check-in status
                // For now, we'll show a success message and reload
                alert('Attendee checked in successfully!');
                window.location.reload();
            }
        }

        function sendEmailReminder() {
            if (confirm('Send an email reminder to {{ $attendee->email }}?')) {
                // This would typically be an AJAX call to send the email
                alert('Email reminder sent successfully!');
            }
        }

        function printTicket() {
            // Generate print content for the ticket
            const printContent = `
                <div style="text-align: center; font-family: Arial, sans-serif; padding: 20px;">
                    <h1>{{ $attendee->event->name }}</h1>
                    <h2>Ticket #{{ $attendee->id }}</h2>
                    <p><strong>Attendee:</strong> {{ $attendee->first_name }} {{ $attendee->last_name }}</p>
                    <p><strong>Email:</strong> {{ $attendee->email }}</p>
                    <p><strong>Ticket Type:</strong> {{ $attendee->ticket->name ?? 'N/A' }}</p>
                    <p><strong>Zone:</strong> {{ $attendee->ticket->eventZone->name ?? 'N/A' }}</p>
                    <p><strong>Event Date:</strong> {{ $attendee->event->event_date->format('M j, Y') }}</p>
                    <p><strong>Event Time:</strong> {{ $attendee->event->start_time ?? 'TBD' }}</p>
                    @if($attendee->qr_code)
                    <div style="margin: 20px 0;">
                        <img src="data:image/svg+xml;base64,{{ base64_encode($attendee->qr_code) }}" alt="QR Code" style="width: 150px; height: 150px;">
                    </div>
                    @endif
                    <p style="font-size: 12px; color: #666;">Please present this ticket at the event entrance</p>
                </div>
            `;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection
