<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zone Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('eventZones.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $eventZone->name ?? 'VIP Section' }}</h1>
                        <p class="text-gray-600 mt-2">Zone details and management</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('eventZones.edit', $eventZone->id ?? 1) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit Zone
                    </a>
                    <form action="{{ route('eventZones.destroy', $eventZone->id ?? 1) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this zone?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition duration-200">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Zone Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Overview Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-blue-600 px-6 py-4">
                        <div class="flex items-center text-white">
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-semibold">Zone Overview</h2>
                                <p class="text-purple-100">Complete zone information and statistics</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Zone Name</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $eventZone->name ?? 'VIP Section' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Zone Type</label>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ ($eventZone->zone_type ?? 'vip') == 'vip' ? 'bg-purple-100 text-purple-800' : 
                                       (($eventZone->zone_type ?? '') == 'premium' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                    <i class="fas fa-crown mr-1"></i>
                                    {{ ucfirst($eventZone->zone_type ?? 'VIP') }}
                                </span>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Capacity</label>
                                <p class="text-lg font-semibold text-gray-900">{{ number_format($eventZone->capacity ?? 100) }} people</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Current Occupancy</label>
                                <div class="flex items-center">
                                    <p class="text-lg font-semibold text-gray-900 mr-2">{{ $eventZone->tickets_sold ?? 45 }}/{{ $eventZone->capacity ?? 100 }}</p>
                                    <span class="text-sm text-green-600 font-medium">
                                        ({{ round((($eventZone->tickets_sold ?? 45) / ($eventZone->capacity ?? 100)) * 100) }}% full)
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full" 
                                         style="width: {{ min(100, (($eventZone->tickets_sold ?? 45) / ($eventZone->capacity ?? 100)) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                        
                        @if($eventZone->description ?? 'Premium seating area with exclusive access to VIP lounge and complimentary refreshments.')
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <p class="text-gray-700">{{ $eventZone->description ?? 'Premium seating area with exclusive access to VIP lounge and complimentary refreshments.' }}</p>
                            </div>
                        @endif
                        
                        <div class="mt-6 grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <i class="fas fa-calendar-plus mr-2 text-blue-500"></i>
                                Created: {{ isset($eventZone->created_at) ? $eventZone->created_at->format('M d, Y') : 'Jan 10, 2024' }}
                            </div>
                            <div>
                                <i class="fas fa-edit mr-2 text-green-500"></i>
                                Updated: {{ isset($eventZone->updated_at) ? $eventZone->updated_at->format('M d, Y') : 'Jan 12, 2024' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Associated Event -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                            Associated Event
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-xl font-semibold text-gray-900">{{ $eventZone->event->name ?? 'Tech Conference 2024' }}</h4>
                                <p class="text-gray-600 mt-1">{{ $eventZone->event->description ?? 'Annual technology conference featuring the latest innovations and industry leaders.' }}</p>
                            </div>
                            <a href="{{ route('events.show', $eventZone->event_id ?? 1) }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                <div>
                                    <p class="text-sm">Event Date</p>
                                    <p class="font-semibold">{{ isset($eventZone->event->event_date) ? date('M d, Y', strtotime($eventZone->event->event_date)) : 'Jan 15, 2024' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-clock mr-2 text-orange-500"></i>
                                <div>
                                    <p class="text-sm">Time</p>
                                    <p class="font-semibold">{{ $eventZone->event->start_time ?? '09:00' }} - {{ $eventZone->event->end_time ?? '17:00' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                <div>
                                    <p class="text-sm">Location</p>
                                    <p class="font-semibold">{{ $eventZone->event->location ?? 'Convention Center' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Types -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-ticket-alt mr-2 text-purple-500"></i>
                            Ticket Types in this Zone
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        @if(isset($eventZone->tickets) && $eventZone->tickets->count() > 0)
                            <div class="space-y-4">
                                @foreach($eventZone->tickets as $ticket)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <h4 class="font-semibold text-gray-900">{{ $ticket->type }}</h4>
                                                <p class="text-gray-600 text-sm">{{ $ticket->description }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-lg font-bold text-purple-600">${{ number_format($ticket->price, 2) }}</p>
                                                <p class="text-sm text-gray-500">{{ $ticket->quantity }} available</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <!-- Sample ticket types for demo -->
                            <div class="space-y-4">
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">VIP Premium</h4>
                                            <p class="text-gray-600 text-sm">Premium seating with all amenities included</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-purple-600">$299.00</p>
                                            <p class="text-sm text-gray-500">25 available</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900">VIP Standard</h4>
                                            <p class="text-gray-600 text-sm">VIP access with standard amenities</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-purple-600">$199.00</p>
                                            <p class="text-sm text-gray-500">30 available</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <div class="mt-6">
                            <a href="{{ route('tickets.create') }}?zone_id={{ $eventZone->id ?? 1 }}" class="text-purple-600 hover:text-purple-800 font-medium">
                                <i class="fas fa-plus mr-2"></i>Add New Ticket Type
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-users mr-2 text-blue-500"></i>
                                <span class="text-sm">Capacity</span>
                            </div>
                            <span class="font-semibold text-lg">{{ number_format($eventZone->capacity ?? 100) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-ticket-alt mr-2 text-purple-500"></i>
                                <span class="text-sm">Sold</span>
                            </div>
                            <span class="font-semibold text-lg text-green-600">{{ $eventZone->tickets_sold ?? 45 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                <span class="text-sm">Available</span>
                            </div>
                            <span class="font-semibold text-lg">{{ ($eventZone->capacity ?? 100) - ($eventZone->tickets_sold ?? 45) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-percentage mr-2 text-orange-500"></i>
                                <span class="text-sm">Occupancy</span>
                            </div>
                            <span class="font-semibold text-lg">{{ round((($eventZone->tickets_sold ?? 45) / ($eventZone->capacity ?? 100)) * 100) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Revenue Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Total Revenue</span>
                                <span class="font-bold text-xl text-green-600">$11,250</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Avg. Ticket Price</span>
                                <span class="font-semibold">$250.00</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Potential Revenue</span>
                                <span class="font-semibold text-purple-600">$25,000</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('eventZones.edit', $eventZone->id ?? 1) }}" class="w-full text-left px-4 py-2 text-purple-600 hover:bg-purple-50 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-edit mr-3"></i>Edit Zone
                        </a>
                        
                        <a href="{{ route('tickets.create') }}?zone_id={{ $eventZone->id ?? 1 }}" class="w-full text-left px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-plus mr-3"></i>Add Ticket Type
                        </a>
                        
                        <a href="{{ route('attendees.index') }}?zone_id={{ $eventZone->id ?? 1 }}" class="w-full text-left px-4 py-2 text-green-600 hover:bg-green-50 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-users mr-3"></i>View Attendees
                        </a>
                        
                        <button onclick="window.print()" class="w-full text-left px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-print mr-3"></i>Print Details
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
