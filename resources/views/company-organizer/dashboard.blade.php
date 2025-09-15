@extends('layouts.app')

@section('title', 'Company Organizer Dashboard')

@section('content')
    <!-- Main Dashboard Content -->
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Welcome Section -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-600">Company Organizer Dashboard - {{ auth()->user()->company->name ?? 'Your Company' }}</p>
        </div>

        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Events -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Company Events</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalEvents) }}</p>
                    </div>
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Attendees -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Attendees</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalAttendees) }}</p>
                    </div>
                    <div class="bg-green-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Recent Check-ins -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Recent Check-ins</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($checkedInAttendees) }}</p>
                    </div>
                    <div class="bg-purple-50 p-3 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Events Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
            </div>
            
            @forelse($upcomingEvents as $event)
                <div class="border border-gray-200 rounded-lg p-4 mb-4 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-900">{{ $event->name }}</h4>
                            <p class="text-sm text-gray-600 mt-1">{{ Str::limit($event->description, 100) }}</p>
                            <div class="flex items-center mt-2 text-sm text-gray-500">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                @if($event->location)
                                    <span class="mx-2">•</span>
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $event->location }}
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col items-end space-y-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($event->type === 'hybrid') bg-blue-100 text-blue-800
                                @elseif($event->type === 'virtual') bg-green-100 text-green-800  
                                @else bg-purple-100 text-purple-800 @endif">
                                {{ ucfirst($event->type) }}
                            </span>
                            <button onclick="copyRegistrationLink('{{ route('public.tickets', $event->id) }}')" 
                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-medium rounded-lg transition duration-200">
                                <i class="fas fa-share-alt mr-1"></i>
                                Share Link
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p>No upcoming events scheduled</p>
                </div>
            @endforelse
        </div>

        <!-- Company Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Company Events</h3>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Event Name</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Type</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Date</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Location</th>
                            <th class="text-right py-3 text-sm font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companyEvents as $event)
                            <tr class="border-b border-gray-100">
                                <td class="py-4">
                                    <div class="font-medium text-gray-900">{{ $event->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($event->description, 50) }}</div>
                                </td>
                                <td class="py-4">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($event->type === 'hybrid') bg-blue-100 text-blue-800
                                        @elseif($event->type === 'virtual') bg-green-100 text-green-800  
                                        @else bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($event->type) }}
                                    </span>
                                </td>
                                <td class="py-4 text-gray-900">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                                </td>
                                <td class="py-4 text-gray-900">{{ $event->location ?? 'TBD' }}</td>
                                <td class="py-4 text-right">
                                    <a href="{{ route('company-organizer.attendees', ['event_id' => $event->id]) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-3">View Attendees</a>
                                    <a href="{{ route('company-organizer.print-tickets.form', ['event_id' => $event->id]) }}" class="text-green-600 hover:text-green-800 text-sm font-medium">Print Tickets</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">No events found for your company</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Check Attendees</h4>
                <p class="text-gray-600 mb-4">View and manage attendee check-ins for company events.</p>
                <a href="{{ route('company-organizer.attendees') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                    View Attendees
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h4 class="text-lg font-semibold text-gray-900 mb-4">Print Tickets</h4>
                <p class="text-gray-600 mb-4">Print tickets and badges for event attendees with search and QR scanning.</p>
                <a href="{{ route('company-organizer.print-tickets.form') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                    Print Tickets
                </a>
            </div>
        </div>

        <!-- QR Code Testing Section -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-xl border border-green-200 p-6 mb-8">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-2 rounded-lg mr-3">
                    <i class="fas fa-qrcode text-green-600 text-lg"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">QR Code Testing</h3>
                    <p class="text-sm text-gray-600">Test the new QR code ticket functionality</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('company-organizer.test-qr') }}" target="_blank" class="bg-white border border-green-200 rounded-lg p-4 hover:bg-green-50 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-eye text-green-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900">View QR Code</div>
                            <div class="text-sm text-gray-600">See generated QR code</div>
                        </div>
                    </div>
                </a>
                
                <a href="{{ route('company-organizer.print-tickets.form') }}" class="bg-white border border-green-200 rounded-lg p-4 hover:bg-green-50 transition-colors">
                    <div class="flex items-center">
                        <i class="fas fa-print text-blue-600 mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900">Print Tickets</div>
                            <div class="text-sm text-gray-600">Generate tickets with QR</div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </main>
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
                showCopyNotification('Registration link copied to clipboard!', 'success');
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
