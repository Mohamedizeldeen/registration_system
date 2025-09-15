@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tickets</h1>
                    <p class="text-gray-600 mt-2">Manage ticket types, pricing, and availability for your events</p>
                </div>
                <a href="{{ route('tickets.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create New Ticket
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="max-w-6xl mx-auto px-4 py-4">
        <div class="bg-white rounded-lg shadow-sm p-4 flex flex-wrap gap-4 items-center">
            <div class="flex items-center space-x-2">
                <i class="fas fa-filter text-gray-500"></i>
                <span class="text-sm font-medium text-gray-700">Filter by:</span>
            </div>
            <select class="px-3 py-1 border border-gray-300 rounded-md text-sm">

                <option>All Events</option>
                @foreach($allEventswithCompany as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                @endforeach
            </select>
            <select class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                <option>All Zones</option>
                <option>VIP Section</option>
                <option>General Admission</option>
                <option>Balcony</option>
            </select>
            <select class="px-3 py-1 border border-gray-300 rounded-md text-sm">
                <option>All Statuses</option>
                <option>Available</option>
                <option>Sold Out</option>
                <option>Limited</option>
            </select>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        @if(isset($tickets) && $tickets->count() > 0)
            <!-- Tickets Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($tickets as $ticket)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-ticket-alt text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $ticket->type }}</h3>
                                        <p class="text-sm text-gray-600">{{ $ticket->event->name ?? 'Event Name' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-2xl font-bold text-blue-600">${{ number_format($ticket->price, 2) }}</div>
                                </div>
                            </div>
                            
                            <div class="space-y-3 mb-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                        <span class="text-sm">Zone</span>
                                    </div>
                                    <span class="font-semibold">{{ $ticket->eventZone->name ?? 'Zone Name' }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-hashtag mr-2 text-green-500"></i>
                                        <span class="text-sm">Quantity</span>
                                    </div>
                                    <span class="font-semibold">{{ number_format($ticket->quantity) }}</span>
                                </div>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-shopping-cart mr-2 text-orange-500"></i>
                                        <span class="text-sm">Sold</span>
                                    </div>
                                    <span class="font-semibold text-green-600">{{ $ticket->sold ?? 0 }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-gray-600">
                                        <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                        <span class="text-sm">Available</span>
                                    </div>
                                    <span class="font-semibold">{{ $ticket->quantity - ($ticket->sold ?? 0) }}</span>
                                </div>
                            </div>
                            
                            <!-- Availability Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600">Availability</span>
                                    <span class="text-sm font-semibold">
                                        {{ round((($ticket->quantity - ($ticket->sold ?? 0)) / $ticket->quantity) * 100) }}% available
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full" 
                                         style="width: {{ (($ticket->quantity - ($ticket->sold ?? 0)) / $ticket->quantity) * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="mb-4">
                                @php
                                    $available = $ticket->quantity - ($ticket->sold ?? 0);
                                    $percentage = ($available / $ticket->quantity) * 100;
                                @endphp
                                
                                @if($percentage == 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Sold Out
                                    </span>
                                @elseif($percentage <= 20)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>Limited
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Available
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    Created {{ $ticket->created_at->diffForHumans() ?? '2 days ago' }}
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tickets.edit', $ticket->id) }}" class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Summary Statistics -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $tickets->count() }}</div>
                    <div class="text-gray-600 mt-1">Total Tickets</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-green-600">${{ number_format($tickets->sum('price'), 0) }}</div>
                    <div class="text-gray-600 mt-1">Total Value</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ number_format($tickets->sum('quantity')) }}</div>
                    <div class="text-gray-600 mt-1">Total Quantity</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-orange-600">${{ number_format($tickets->avg('price'), 0) }}</div>
                    <div class="text-gray-600 mt-1">Avg. Price</div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-ticket-alt text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Tickets Found</h3>
                <p class="text-gray-500 mb-6">Start by creating ticket types for your events and zones.</p>
                <a href="{{ route('tickets.create') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                    Create Your First Ticket
                </a>
            </div>
        @endif

        <!-- Sample Tickets for Demo (when no real data) -->
        @if(!isset($tickets))
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Sample Ticket 1 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-ticket-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">VIP Premium</h3>
                                    <p class="text-sm text-gray-600">Tech Conference 2024</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">$299.00</div>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                    <span class="text-sm">Zone</span>
                                </div>
                                <span class="font-semibold">VIP Section</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>
                                    <span class="text-sm">Quantity</span>
                                </div>
                                <span class="font-semibold">50</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-shopping-cart mr-2 text-orange-500"></i>
                                    <span class="text-sm">Sold</span>
                                </div>
                                <span class="font-semibold text-green-600">32</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                    <span class="text-sm">Available</span>
                                </div>
                                <span class="font-semibold">18</span>
                            </div>
                        </div>
                        
                        <!-- Availability Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Availability</span>
                                <span class="text-sm font-semibold">36% available</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full" style="width: 36%"></div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Limited
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-xs text-gray-500">
                                Created 2 days ago
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="text-yellow-600 hover:text-yellow-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Ticket 2 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-ticket-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">General Admission</h3>
                                    <p class="text-sm text-gray-600">Tech Conference 2024</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">$99.00</div>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                    <span class="text-sm">Zone</span>
                                </div>
                                <span class="font-semibold">General Admission</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>
                                    <span class="text-sm">Quantity</span>
                                </div>
                                <span class="font-semibold">500</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-shopping-cart mr-2 text-orange-500"></i>
                                    <span class="text-sm">Sold</span>
                                </div>
                                <span class="font-semibold text-green-600">234</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                    <span class="text-sm">Available</span>
                                </div>
                                <span class="font-semibold">266</span>
                            </div>
                        </div>
                        
                        <!-- Availability Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Availability</span>
                                <span class="text-sm font-semibold">53% available</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full" style="width: 53%"></div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Available
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-xs text-gray-500">
                                Created 3 days ago
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="text-yellow-600 hover:text-yellow-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample Ticket 3 -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-ticket-alt text-white text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Student Discount</h3>
                                    <p class="text-sm text-gray-600">Tech Conference 2024</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-blue-600">$49.00</div>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-map-marker-alt mr-2 text-purple-500"></i>
                                    <span class="text-sm">Zone</span>
                                </div>
                                <span class="font-semibold">Balcony</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-hashtag mr-2 text-green-500"></i>
                                    <span class="text-sm">Quantity</span>
                                </div>
                                <span class="font-semibold">100</span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-shopping-cart mr-2 text-orange-500"></i>
                                    <span class="text-sm">Sold</span>
                                </div>
                                <span class="font-semibold text-green-600">89</span>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-check-circle mr-2 text-blue-500"></i>
                                    <span class="text-sm">Available</span>
                                </div>
                                <span class="font-semibold">11</span>
                            </div>
                        </div>
                        
                        <!-- Availability Progress Bar -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-600">Availability</span>
                                <span class="text-sm font-semibold">11% available</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-green-500 to-blue-600 h-2 rounded-full" style="width: 11%"></div>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Almost Sold Out
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="text-xs text-gray-500">
                                Created 1 week ago
                            </div>
                            
                            <div class="flex space-x-2">
                                <a href="#" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="text-yellow-600 hover:text-yellow-800">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="mt-12 grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-blue-600">3</div>
                    <div class="text-gray-600 mt-1">Total Tickets</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-green-600">$447</div>
                    <div class="text-gray-600 mt-1">Total Value</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-purple-600">650</div>
                    <div class="text-gray-600 mt-1">Total Quantity</div>
                </div>
                <div class="bg-white rounded-lg shadow-md p-6 text-center">
                    <div class="text-3xl font-bold text-orange-600">$149</div>
                    <div class="text-gray-600 mt-1">Avg. Price</div>
                </div>
            </div>
        @endif
    </div>
@endsection
