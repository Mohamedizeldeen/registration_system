<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('tickets.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $ticket->type ?? 'VIP Premium' }}</h1>
                        <p class="text-gray-600 mt-2">Ticket details and sales information</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('tickets.edit', $ticket->id ?? 1) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit Ticket
                    </a>
                    <form action="{{ route('tickets.destroy', $ticket->id ?? 1) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
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
            <!-- Ticket Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Overview Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4">
                        <div class="flex items-center justify-between text-white">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-4">
                                    <i class="fas fa-ticket-alt text-xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-semibold">Ticket Overview</h2>
                                    <p class="text-blue-100">Complete ticket information and analytics</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-3xl font-bold">${{ number_format($ticket->price ?? 299, 2) }}</div>
                                <div class="text-blue-100">Per ticket</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ticket Type</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $ticket->type ?? 'VIP Premium' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $ticket->event->name ?? 'Tech Conference 2024' }}</p>
                                <p class="text-sm text-gray-600">{{ isset($ticket->event->event_date) ? date('M d, Y', strtotime($ticket->event->event_date)) : 'Jan 15, 2024' }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Zone</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $ticket->eventZone->name ?? 'VIP Section' }}</p>
                                <p class="text-sm text-gray-600">Capacity: {{ number_format($ticket->eventZone->capacity ?? 100) }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Per Person</label>
                                <p class="text-lg font-semibold text-gray-900">{{ $ticket->max_per_person ?? 2 }} tickets</p>
                            </div>
                        </div>
                        
                        <!-- Availability Progress -->
                        <div class="mt-6">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block text-sm font-medium text-gray-700">Ticket Availability</label>
                                <span class="text-sm font-semibold">{{ ($ticket->quantity ?? 50) - ($ticket->sold ?? 15) }}/{{ $ticket->quantity ?? 50 }} available</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-500 to-blue-600 h-3 rounded-full" 
                                     style="width: {{ (($ticket->quantity ?? 50) - ($ticket->sold ?? 15)) / ($ticket->quantity ?? 50) * 100 }}%"></div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-500 mt-1">
                                <span>{{ round((($ticket->sold ?? 15) / ($ticket->quantity ?? 50)) * 100) }}% sold</span>
                                <span>{{ round((($ticket->quantity ?? 50) - ($ticket->sold ?? 15)) / ($ticket->quantity ?? 50) * 100) }}% available</span>
                            </div>
                        </div>
                        
                        @if($ticket->description ?? 'Premium seating with exclusive amenities, complimentary refreshments, and priority access.')
                            <div class="mt-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <p class="text-gray-700">{{ $ticket->description ?? 'Premium seating with exclusive amenities, complimentary refreshments, and priority access.' }}</p>
                            </div>
                        @endif
                        
                        <!-- Sale Period -->
                        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sale Start</label>
                                <p class="text-gray-900 flex items-center">
                                    <i class="fas fa-play-circle text-green-500 mr-2"></i>
                                    {{ isset($ticket->sale_start_date) ? date('M d, Y H:i', strtotime($ticket->sale_start_date)) : 'Dec 1, 2023 09:00' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sale End</label>
                                <p class="text-gray-900 flex items-center">
                                    <i class="fas fa-stop-circle text-red-500 mr-2"></i>
                                    {{ isset($ticket->sale_end_date) ? date('M d, Y H:i', strtotime($ticket->sale_end_date)) : 'Jan 14, 2024 23:59' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-6 grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <i class="fas fa-calendar-plus mr-2 text-blue-500"></i>
                                Created: {{ isset($ticket->created_at) ? $ticket->created_at->format('M d, Y') : 'Dec 1, 2023' }}
                            </div>
                            <div>
                                <i class="fas fa-edit mr-2 text-green-500"></i>
                                Updated: {{ isset($ticket->updated_at) ? $ticket->updated_at->format('M d, Y') : 'Jan 5, 2024' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sales Analytics -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-line mr-2 text-blue-500"></i>
                            Sales Analytics
                        </h3>
                    </div>
                    
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                            <div class="text-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg">
                                <div class="text-2xl font-bold text-blue-600">{{ $ticket->sold ?? 15 }}</div>
                                <div class="text-sm text-gray-600">Tickets Sold</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg">
                                <div class="text-2xl font-bold text-green-600">${{ number_format(($ticket->sold ?? 15) * ($ticket->price ?? 299), 0) }}</div>
                                <div class="text-sm text-gray-600">Total Revenue</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">{{ ($ticket->quantity ?? 50) - ($ticket->sold ?? 15) }}</div>
                                <div class="text-sm text-gray-600">Remaining</div>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg">
                                <div class="text-2xl font-bold text-orange-600">${{ number_format((($ticket->quantity ?? 50) - ($ticket->sold ?? 15)) * ($ticket->price ?? 299), 0) }}</div>
                                <div class="text-sm text-gray-600">Potential Revenue</div>
                            </div>
                        </div>

                        <!-- Sales Timeline (Sample Data) -->
                        <div class="mt-8">
                            <h4 class="font-medium text-gray-900 mb-4">Recent Sales Activity</h4>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                                        <span class="text-sm">3 tickets sold</span>
                                    </div>
                                    <span class="text-xs text-gray-500">2 hours ago</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                                        <span class="text-sm">5 tickets sold</span>
                                    </div>
                                    <span class="text-xs text-gray-500">1 day ago</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                                        <span class="text-sm">7 tickets sold</span>
                                    </div>
                                    <span class="text-xs text-gray-500">3 days ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendees -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-purple-500"></i>
                                Recent Attendees
                            </h3>
                            <a href="{{ route('attendees.index') }}?ticket_id={{ $ticket->id ?? 1 }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                View All
                            </a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <!-- Sample attendees data -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold text-sm">JD</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">John Doe</p>
                                        <p class="text-sm text-gray-600">john.doe@email.com</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold text-green-600">Paid</span>
                                    <p class="text-xs text-gray-500">2 tickets</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold text-sm">JS</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Jane Smith</p>
                                        <p class="text-sm text-gray-600">jane.smith@email.com</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold text-green-600">Paid</span>
                                    <p class="text-xs text-gray-500">1 ticket</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-white font-semibold text-sm">MB</span>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">Mike Brown</p>
                                        <p class="text-sm text-gray-600">mike.brown@email.com</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-semibold text-yellow-600">Pending</span>
                                    <p class="text-xs text-gray-500">1 ticket</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Status</h3>
                    @php
                        $available = ($ticket->quantity ?? 50) - ($ticket->sold ?? 15);
                        $percentage = ($available / ($ticket->quantity ?? 50)) * 100;
                    @endphp
                    
                    @if($percentage == 0)
                        <div class="flex items-center justify-center p-4 bg-red-100 rounded-lg">
                            <i class="fas fa-times-circle text-red-600 mr-2"></i>
                            <span class="font-semibold text-red-800">Sold Out</span>
                        </div>
                    @elseif($percentage <= 20)
                        <div class="flex items-center justify-center p-4 bg-orange-100 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                            <span class="font-semibold text-orange-800">Limited Availability</span>
                        </div>
                    @else
                        <div class="flex items-center justify-center p-4 bg-green-100 rounded-lg">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            <span class="font-semibold text-green-800">Available</span>
                        </div>
                    @endif
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Stats</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-hashtag mr-2 text-blue-500"></i>
                                <span class="text-sm">Total Quantity</span>
                            </div>
                            <span class="font-semibold text-lg">{{ number_format($ticket->quantity ?? 50) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-shopping-cart mr-2 text-green-500"></i>
                                <span class="text-sm">Sold</span>
                            </div>
                            <span class="font-semibold text-lg text-green-600">{{ $ticket->sold ?? 15 }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-check-circle mr-2 text-purple-500"></i>
                                <span class="text-sm">Available</span>
                            </div>
                            <span class="font-semibold text-lg">{{ ($ticket->quantity ?? 50) - ($ticket->sold ?? 15) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-percentage mr-2 text-orange-500"></i>
                                <span class="text-sm">Sold Rate</span>
                            </div>
                            <span class="font-semibold text-lg">{{ round((($ticket->sold ?? 15) / ($ticket->quantity ?? 50)) * 100) }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Revenue -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue</h3>
                    <div class="space-y-4">
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Current Revenue</span>
                                <span class="font-bold text-xl text-green-600">${{ number_format(($ticket->sold ?? 15) * ($ticket->price ?? 299), 0) }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Potential Revenue</span>
                                <span class="font-semibold text-purple-600">${{ number_format(($ticket->quantity ?? 50) * ($ticket->price ?? 299), 0) }}</span>
                            </div>
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Remaining Potential</span>
                                <span class="font-semibold text-blue-600">${{ number_format((($ticket->quantity ?? 50) - ($ticket->sold ?? 15)) * ($ticket->price ?? 299), 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('tickets.edit', $ticket->id ?? 1) }}" class="w-full text-left px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-edit mr-3"></i>Edit Ticket
                        </a>
                        
                        <a href="{{ route('attendees.index') }}?ticket_id={{ $ticket->id ?? 1 }}" class="w-full text-left px-4 py-2 text-green-600 hover:bg-green-50 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-users mr-3"></i>View Attendees
                        </a>
                        
                        <a href="{{ route('payments.index') }}?ticket_id={{ $ticket->id ?? 1 }}" class="w-full text-left px-4 py-2 text-purple-600 hover:bg-purple-50 rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-credit-card mr-3"></i>View Payments
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
