@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('tickets.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Ticket</h1>
                        <p class="text-gray-600 mt-2">Update ticket configuration and settings</p>
                    </div>
                </div>
                <a href="{{ route('tickets.show', $ticket->id ?? 1) }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-eye mr-2"></i>View Ticket
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Current Ticket Info -->
            <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-blue-50 to-purple-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-ticket-alt text-white text-lg"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">
                                {{ $ticket->type ?? 'VIP Premium' }}
                            </h2>
                            <p class="text-gray-600">
                                Current price: ${{ number_format($ticket->price ?? 299, 2) }} • 
                                Available: {{ ($ticket->quantity ?? 50) - ($ticket->sold ?? 15) }}/{{ $ticket->quantity ?? 50 }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-blue-600">${{ number_format($ticket->price ?? 299, 2) }}</div>
                        <div class="text-sm text-gray-500">{{ round((($ticket->sold ?? 15) / ($ticket->quantity ?? 50)) * 100) }}% sold</div>
                    </div>
                </div>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 m-6 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('tickets.update', $ticket->id ?? 1) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ticket Type -->
                    <div class="md:col-span-1">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>Ticket Type *
                        </label>
                        <input type="text" id="type" name="type" 
                               value="{{ old('type', $ticket->type ?? 'VIP Premium') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., VIP Premium, General Admission">
                    </div>

                    <!-- Price -->
                    <div class="md:col-span-1">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Price *
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">$</span>
                            <input type="number" id="price" name="price" 
                                   value="{{ old('price', $ticket->price ?? 299) }}" required min="0" step="0.01"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ $ticket->sold ?? 15 }} tickets already sold at current price
                        </p>
                    </div>
                </div>

                <!-- Event and Zone Selection -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Event Selection -->
                    <div class="md:col-span-1">
                        <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>Event *
                        </label>
                        <select id="event_id" name="event_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select an event</option>
                            @if(isset($events))
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" 
                                            {{ old('event_id', $ticket->event_id ?? 1) == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }} - {{ date('M d, Y', strtotime($event->event_date)) }}
                                    </option>
                                @endforeach
                            @else
                                <!-- Sample options for demo -->
                                <option value="1" {{ old('event_id', $ticket->event_id ?? 1) == 1 ? 'selected' : '' }}>
                                    Tech Conference 2024 - Jan 15, 2024
                                </option>
                                <option value="2" {{ old('event_id', $ticket->event_id ?? 1) == 2 ? 'selected' : '' }}>
                                    Music Festival Summer - Jul 20, 2024
                                </option>
                                <option value="3" {{ old('event_id', $ticket->event_id ?? 1) == 3 ? 'selected' : '' }}>
                                    Business Summit - Mar 10, 2024
                                </option>
                            @endif
                        </select>
                    </div>

                    <!-- Zone Selection -->
                    <div class="md:col-span-1">
                        <label for="event_zone_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-2 text-orange-500"></i>Zone *
                        </label>
                        <select id="event_zone_id" name="event_zone_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a zone</option>
                            @if(isset($eventZones))
                                @foreach($eventZones as $zone)
                                    <option value="{{ $zone->id }}" 
                                            {{ old('event_zone_id', $ticket->event_zone_id ?? 1) == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }} (Capacity: {{ number_format($zone->capacity) }})
                                    </option>
                                @endforeach
                            @else
                                <!-- Sample options for demo -->
                                <option value="1" {{ old('event_zone_id', $ticket->event_zone_id ?? 1) == 1 ? 'selected' : '' }}>
                                    VIP Section (Capacity: 100)
                                </option>
                                <option value="2" {{ old('event_zone_id', $ticket->event_zone_id ?? 1) == 2 ? 'selected' : '' }}>
                                    General Admission (Capacity: 500)
                                </option>
                                <option value="3" {{ old('event_zone_id', $ticket->event_zone_id ?? 1) == 3 ? 'selected' : '' }}>
                                    Balcony (Capacity: 150)
                                </option>
                            @endif
                        </select>
                    </div>
                </div>

                <!-- Quantity and Availability -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Quantity -->
                    <div class="md:col-span-1">
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-hashtag mr-2 text-indigo-500"></i>Available Quantity *
                        </label>
                        <input type="number" id="quantity" name="quantity" 
                               value="{{ old('quantity', $ticket->quantity ?? 50) }}" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Number of tickets available">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-warning mr-1"></i>
                            Cannot be less than sold tickets ({{ $ticket->sold ?? 15 }})
                        </p>
                    </div>

                    <!-- Sales Start Date -->
                    <div class="md:col-span-1">
                        <label for="sale_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-play-circle mr-2 text-green-500"></i>Sale Start Date
                        </label>
                        <input type="datetime-local" id="sale_start_date" name="sale_start_date" 
                               value="{{ old('sale_start_date', isset($ticket->sale_start_date) ? date('Y-m-d\TH:i', strtotime($ticket->sale_start_date)) : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <!-- Sales End Date and Max Per Person -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <!-- Sales End Date -->
                    <div class="md:col-span-1">
                        <label for="sale_end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-stop-circle mr-2 text-red-500"></i>Sale End Date
                        </label>
                        <input type="datetime-local" id="sale_end_date" name="sale_end_date" 
                               value="{{ old('sale_end_date', isset($ticket->sale_end_date) ? date('Y-m-d\TH:i', strtotime($ticket->sale_end_date)) : '') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Max Per Person -->
                    <div class="md:col-span-1">
                        <label for="max_per_person" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-purple-500"></i>Max Per Person
                        </label>
                        <input type="number" id="max_per_person" name="max_per_person" 
                               value="{{ old('max_per_person', $ticket->max_per_person ?? 2) }}" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Maximum tickets per person">
                    </div>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-gray-500"></i>Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="Describe what's included with this ticket type...">{{ old('description', $ticket->description ?? 'Premium seating with exclusive amenities, complimentary refreshments, and priority access.') }}</textarea>
                </div>

                <!-- Current Sales Information -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-chart-bar mr-2"></i>Current Sales Status
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $ticket->sold ?? 15 }}</div>
                            <div class="text-xs text-gray-600">Tickets Sold</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ ($ticket->quantity ?? 50) - ($ticket->sold ?? 15) }}</div>
                            <div class="text-xs text-gray-600">Available</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">${{ number_format(($ticket->sold ?? 15) * ($ticket->price ?? 299), 0) }}</div>
                            <div class="text-xs text-gray-600">Revenue</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ round((($ticket->sold ?? 15) / ($ticket->quantity ?? 50)) * 100) }}%</div>
                            <div class="text-xs text-gray-600">Sold</div>
                        </div>
                    </div>
                </div>

                <!-- Quantity Change Warning -->
                <div id="quantity-warning" class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Quantity Change Warning</h4>
                            <p class="text-yellow-700 text-sm mt-1">
                                You cannot reduce quantity below the number of already sold tickets ({{ $ticket->sold ?? 15 }}). 
                                This could cause issues with existing reservations.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Price Change Impact -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-calculator mr-2"></i>Revenue Impact Calculator
                    </h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-lg font-bold text-blue-600" id="potential-revenue">${{ number_format(($ticket->quantity ?? 50) * ($ticket->price ?? 299), 0) }}</div>
                            <div class="text-xs text-gray-600">Potential Revenue</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-green-600" id="current-revenue">${{ number_format(($ticket->sold ?? 15) * ($ticket->price ?? 299), 0) }}</div>
                            <div class="text-xs text-gray-600">Current Revenue</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-purple-600" id="remaining-revenue">${{ number_format((($ticket->quantity ?? 50) - ($ticket->sold ?? 15)) * ($ticket->price ?? 299), 0) }}</div>
                            <div class="text-xs text-gray-600">Remaining Potential</div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('tickets.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <div class="flex space-x-3">
                        <a href="{{ route('tickets.show', $ticket->id ?? 1) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200">
                            <i class="fas fa-eye mr-2"></i>View Ticket
                        </a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                            <i class="fas fa-save mr-2"></i>Update Ticket
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Quantity change warning
        const quantityInput = document.getElementById('quantity');
        const warningDiv = document.getElementById('quantity-warning');
        const currentSold = {{ $ticket->sold ?? 15 }};
        
        quantityInput.addEventListener('input', function() {
            const newQuantity = parseInt(this.value);
            if (newQuantity < currentSold) {
                warningDiv.classList.remove('hidden');
            } else {
                warningDiv.classList.add('hidden');
            }
            updateCalculator();
        });

        // Calculator functionality
        function updateCalculator() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const sold = currentSold;
            
            const potentialRevenue = price * quantity;
            const currentRevenue = price * sold;
            const remainingRevenue = price * (quantity - sold);
            
            document.getElementById('potential-revenue').textContent = '$' + potentialRevenue.toLocaleString();
            document.getElementById('current-revenue').textContent = '$' + currentRevenue.toLocaleString();
            document.getElementById('remaining-revenue').textContent = '$' + remainingRevenue.toLocaleString();
        }

        // Update calculator on input change
        document.getElementById('price').addEventListener('input', updateCalculator);
    </script>
@endsection
