@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $event->name }}</h1>
                        <p class="text-gray-600 mt-2">Event Details</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button onclick="copyRegistrationLink('{{ route('public.tickets', $event->id) }}')"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-share-alt mr-2"></i>Share Registration Link
                    </button>
                    <a href="{{ route('events.edit', $event->id) }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit Event
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Event Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Event Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($event->logo && file_exists(public_path('storage/logos/' . $event->logo)))
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/logos/' . $event->logo) }}" alt="{{ $event->name }}"
                                    class="h-20 w-20 object-cover rounded-lg">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $event->name }}</h3>
                                    <p class="text-gray-600">{{ ucfirst($event->type) }} Event</p>
                                </div>
                            </div>
                        @endif

                        <div>
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                            <p class="text-gray-600">{{ $event->description }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-1">Event Type</h4>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                        {{ $event->type === 'hybrid' ? 'bg-purple-100 text-purple-800' :
        ($event->type === 'virtual' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($event->type) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-1">Date</h4>
                                <p class="text-gray-600">{{ $event->event_date->format('F j, Y') }}</p>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-1">Time</h4>
                                <p class="text-gray-600">{{ \Carbon\Carbon::parse($event->start_time)->format('g:i A') }} -
                                    {{ \Carbon\Carbon::parse($event->end_time)->format('g:i A') }}
                                </p>
                            </div>
                            @if($event->location)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-700 mb-1">Location</h4>
                                    <p class="text-gray-600">{{ $event->location }}</p>
                                </div>
                            @endif
                        </div>

                        @if($event->banner_url)
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Banner</h4>
                                <img src="{{ $event->banner_url }}" alt="Event Banner"
                                    class="w-full h-64 object-cover rounded-lg">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Contact Information -->
                @if($event->email || $event->phone || $event->facebook || $event->website || $event->instagram || $event->linkedin)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Contact Information</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            @if($event->email)
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                    <a href="mailto:{{ $event->email }}"
                                        class="text-blue-600 hover:text-blue-800">{{ $event->email }}</a>
                                </div>
                            @endif
                            @if($event->phone)
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-phone text-gray-400"></i>
                                    <a href="tel:{{ $event->phone }}"
                                        class="text-blue-600 hover:text-blue-800">{{ $event->phone }}</a>
                                </div>
                            @endif
                            @if ($event->website)
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-globe text-gray-400"></i>
                                    <a href="{{ $event->website }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800">{{ $event->website }}</a>
                                </div>
                            @endif
                            @if ($event->facebook)
                                <div class="flex items-center space-x-3">
                                    <i class="fab fa-facebook text-gray-400"></i>
                                    <a href="{{ $event->facebook }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800">Facebook</a>
                                </div>
                            @endif
                            @if ($event->instagram)
                                <div class="flex items-center space-x-3">
                                    <i class="fab fa-instagram text-gray-400"></i>
                                    <a href="{{ $event->instagram }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800">Instagram</a>
                                </div>
                            @endif
                            @if ($event->linkedin)
                                <div class="flex items-center space-x-3">
                                    <i class="fab fa-linkedin text-gray-400"></i>
                                    <a href="{{ $event->linkedin }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800">LinkedIn</a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Event Zones -->
                @if($event->eventZones->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Event Zones ({{ $event->eventZones->count() }})</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($event->eventZones as $zone)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <h3 class="font-medium text-gray-900">{{ $zone->name }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">Capacity: {{ number_format($zone->capacity) }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Tickets -->
                @if($event->tickets->count() > 0)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">Ticket Types ({{ $event->tickets->count() }})</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($event->tickets as $ticket)
                                    <div class="flex justify-between items-center p-4 border border-gray-200 rounded-lg">
                                        <div>
                                            <h3 class="font-medium text-gray-900">{{ $ticket->name }}</h3>
                                            @if($ticket->info)
                                                <p class="text-sm text-gray-600">{{ $ticket->info }}</p>
                                            @endif
                                            <p class="text-sm text-gray-500">Quantity: {{ $ticket->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-gray-900">{{ $ticket->currency->symbol ?? '$' }}
                                                {{ number_format($ticket->price, 2) }}
                                            </p>
                                            <p class="text-sm text-gray-600">{{ $ticket->currency->code ?? 'USD' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Event Statistics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Statistics</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="text-center">
                            <p class="text-3xl font-bold text-blue-600">{{ $event->event_zones_count ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Zones</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-green-600">{{ $event->tickets_count ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Ticket Types</p>
                        </div>
                        <div class="text-center">
                            <p class="text-3xl font-bold text-purple-600">{{ $event->attendees_count ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Attendees</p>
                        </div>
                        <div class="text-center border-t border-gray-200 pt-4">
                            <p class="text-2xl font-bold text-orange-600">
                                {{ $event->tickets->first()->currency->symbol ?? '$' }}
                                {{ number_format($event->tickets->sum('price') ?? 0, 2) }}
                            </p>
                            <p class="text-sm text-gray-600">Total Ticket Value</p>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Quick Actions</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="{{ route('eventZones.create') }}?event_id={{ $event->id }}"
                            class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center justify-center">
                            <i class="fas fa-plus mr-2"></i>Add Zone
                        </a>
                        <a href="{{ route('tickets.create') }}?event_id={{ $event->id }}"
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 flex items-center justify-center">
                            <i class="fas fa-ticket-alt mr-2"></i>Add Tickets
                        </a>
                        <a href="{{ route('attendees.index') }}?event_id={{ $event->id }}"
                            class="w-full px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200 flex items-center justify-center">
                            <i class="fas fa-users mr-2"></i>View Attendees
                        </a>
                        <a href="{{ route('events.edit', $event->id) }}"
                            class="w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md transition duration-200 flex items-center justify-center">
                            <i class="fas fa-edit mr-2"></i>Edit Event
                        </a>
                    </div>
                </div>

                <!-- Event Timeline -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900">Timeline</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Event Created</p>
                                <p class="text-xs text-gray-500">{{ $event->created_at->format('M j, Y') }}</p>
                            </div>
                        </div>
                        @if($event->updated_at != $event->created_at)
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Last Updated</p>
                                    <p class="text-xs text-gray-500">{{ $event->updated_at->format('M j, Y') }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Event Date</p>
                                <p class="text-xs text-gray-500">{{ $event->event_date->format('M j, Y') }}</p>
                            </div>
                        </div>
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
            notification.className = `copy-notification fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform transition-all duration-300 ${type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
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