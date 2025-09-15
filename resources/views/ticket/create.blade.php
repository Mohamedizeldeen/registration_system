@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="{{ route('tickets.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Ticket</h1>
                    <p class="text-gray-600 mt-2">Set up a new ticket type with pricing and availability</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Form Header -->
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-ticket-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Ticket Information</h2>
                        <p class="text-gray-600">Configure ticket details, pricing, and availability</p>
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
            <form action="{{ route('tickets.store') }}" method="POST" class="p-6">
                @csrf
                
                <!-- Basic Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Ticket Type -->
                    <div class="md:col-span-1">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>Ticket Type *
                        </label>
                        <input type="text" id="type" name="type" value="{{ old('type') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="e.g., VIP Premium, General Admission">
                    </div>

                    <div class="md:col-span-1">
                        <label for="currencies" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-2 text-purple-500"></i>Currency *
                        </label>
                        <select id="currency_id" name="currency_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select a currency</option>
                            @if(isset($currencies))
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency->id }}" {{ old('currency_id') == $currency->id ? 'selected' : '' }}>
                                        {{ $currency->name }}
                                    </option>
                                @endforeach
                            @else
                                <!-- Sample options for demo -->
                                <option value="1">Tech Conference 2024 - Jan 15, 2024</option>
                                <option value="2">Music Festival Summer - Jul 20, 2024</option>
                                <option value="3">Business Summit - Mar 10, 2024</option>
                            @endif
                        </select>
                    </div>
                    
                    <div class="md:col-span-1">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-blue-500"></i>Ticket Name *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
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
                            <input type="number" id="price" name="price" value="{{ old('price') }}" required min="0" step="0.01"
                                   class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="0.00">
                        </div>
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
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }} - {{ date('M d, Y', strtotime($event->event_date)) }}
                                    </option>
                                @endforeach
                            @else
                                <!-- Sample options for demo -->
                                <option value="1">Tech Conference 2024 - Jan 15, 2024</option>
                                <option value="2">Music Festival Summer - Jul 20, 2024</option>
                                <option value="3">Business Summit - Mar 10, 2024</option>
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
                                    <option value="{{ $zone->id }}" {{ old('event_zone_id') == $zone->id ? 'selected' : '' }}>
                                        {{ $zone->name }} (Capacity: {{ number_format($zone->capacity) }})
                                    </option>
                                @endforeach
                            @else
                                <!-- Sample options for demo -->
                                <option value="1">VIP Section (Capacity: 100)</option>
                                <option value="2">General Admission (Capacity: 500)</option>
                                <option value="3">Balcony (Capacity: 150)</option>
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
                        <input type="number" id="quantity" name="quantity" value="{{ old('quantity') }}" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="Number of tickets available">
                    </div>

                    <!-- Sales Start Date -->
                    <div class="md:col-span-1">
                        <label for="sale_start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-play-circle mr-2 text-green-500"></i>Sale Start Date
                        </label>
                        <input type="datetime-local" id="sale_start_date" name="sale_start_date" value="{{ old('sale_start_date') }}"
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
                        <input type="datetime-local" id="sale_end_date" name="sale_end_date" value="{{ old('sale_end_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>

                    <!-- Max Per Person -->
                    <div class="md:col-span-1">
                        <label for="max_per_person" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2 text-purple-500"></i>Max Per Person
                        </label>
                        <input type="number" id="max_per_person" name="max_per_person" value="{{ old('max_per_person', 5) }}" min="1"
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
                              placeholder="Describe what's included with this ticket type...">{{ old('description') }}</textarea>
                </div>

                <!-- Quick Ticket Templates -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-magic mr-2"></i>Quick Templates
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <button type="button" class="template-btn p-3 text-left bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                data-type="VIP Premium" data-price="299" data-quantity="50" data-max="2" 
                                data-description="Premium seating with exclusive amenities, complimentary refreshments, and priority access.">
                            <div class="font-semibold text-purple-600">VIP Premium</div>
                            <div class="text-sm text-gray-600">$299 • 50 tickets • Max 2 per person</div>
                        </button>
                        
                        <button type="button" class="template-btn p-3 text-left bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                data-type="General Admission" data-price="99" data-quantity="500" data-max="5"
                                data-description="Standard admission with access to all general areas and basic amenities.">
                            <div class="font-semibold text-blue-600">General Admission</div>
                            <div class="text-sm text-gray-600">$99 • 500 tickets • Max 5 per person</div>
                        </button>
                        
                        <button type="button" class="template-btn p-3 text-left bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                data-type="Student Discount" data-price="49" data-quantity="100" data-max="1"
                                data-description="Discounted ticket for students with valid ID. Limited availability.">
                            <div class="font-semibold text-green-600">Student Discount</div>
                            <div class="text-sm text-gray-600">$49 • 100 tickets • Max 1 per person</div>
                        </button>
                    </div>
                </div>

                <!-- Price Calculator -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-calculator mr-2"></i>Revenue Calculator
                    </h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-lg font-bold text-blue-600" id="potential-revenue">$0</div>
                            <div class="text-xs text-gray-600">Potential Revenue</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-green-600" id="avg-price">$0</div>
                            <div class="text-xs text-gray-600">Price Per Ticket</div>
                        </div>
                        <div>
                            <div class="text-lg font-bold text-purple-600" id="total-tickets">0</div>
                            <div class="text-xs text-gray-600">Total Tickets</div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('tickets.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Template functionality
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('type').value = this.dataset.type;
                document.getElementById('price').value = this.dataset.price;
                document.getElementById('quantity').value = this.dataset.quantity;
                document.getElementById('max_per_person').value = this.dataset.max;
                document.getElementById('description').value = this.dataset.description;
                
                // Update calculator
                updateCalculator();
                
                // Visual feedback
                this.classList.add('bg-blue-100', 'border-blue-300');
                setTimeout(() => {
                    this.classList.remove('bg-blue-100', 'border-blue-300');
                }, 1000);
            });
        });

        // Calculator functionality
        function updateCalculator() {
            const price = parseFloat(document.getElementById('price').value) || 0;
            const quantity = parseInt(document.getElementById('quantity').value) || 0;
            const potential = price * quantity;
            
            document.getElementById('potential-revenue').textContent = '$' + potential.toLocaleString();
            document.getElementById('avg-price').textContent = '$' + price.toFixed(2);
            document.getElementById('total-tickets').textContent = quantity.toLocaleString();
        }

        // Update calculator on input change
        document.getElementById('price').addEventListener('input', updateCalculator);
        document.getElementById('quantity').addEventListener('input', updateCalculator);

        // Zone filtering based on event selection
        document.getElementById('event_id').addEventListener('change', function() {
            // In a real application, this would filter zones based on the selected event
            // For demo purposes, we'll just show all zones
        });
    </script>
@endsection
