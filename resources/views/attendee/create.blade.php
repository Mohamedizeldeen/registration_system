@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Register New Attendee</h1>
                    <p class="text-gray-600 mt-2">Add a new attendee to your event</p>
                </div>
                <a href="{{ route('attendees.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Attendees
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Attendee Information</h2>
                <p class="text-gray-600 mt-1">Please fill out all required fields to register the attendee</p>
            </div>

            <form action="{{ route('attendees.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Event Selection -->
                <div>
                    <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Event <span class="text-red-500">*</span>
                    </label>
                    <select name="event_id" id="event_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select an event</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }} - {{ $event->event_date->format('M j, Y') }}
                            </option>
                        @endforeach
                    </select>
                    @error('event_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ticket Selection -->
                <div>
                    <label for="ticket_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Ticket Type <span class="text-red-500">*</span>
                    </label>
                    <select name="ticket_id" id="ticket_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required disabled>
                        <option value="">Select an event first</option>
                    </select>
                    @error('ticket_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Personal Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('first_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('last_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Phone Number
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Professional Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="company" class="block text-sm font-medium text-gray-700 mb-2">
                                Company
                            </label>
                            <input type="text" name="company" id="company" value="{{ old('company') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('company')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="job_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Job Title
                            </label>
                            <input type="text" name="job_title" id="job_title" value="{{ old('job_title') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('job_title')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                City
                            </label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country
                            </label>
                            <input type="text" name="country" id="country" value="{{ old('country') }}" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('country')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h3>
                    
                    <div>
                        <label for="dietary_restrictions" class="block text-sm font-medium text-gray-700 mb-2">
                            Dietary Restrictions
                        </label>
                        <textarea name="dietary_restrictions" id="dietary_restrictions" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('dietary_restrictions') }}</textarea>
                        @error('dietary_restrictions')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes
                        </label>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('attendees.index') }}" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                        Register Attendee
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for dynamic ticket loading -->
    <script>
        document.getElementById('event_id').addEventListener('change', function() {
            const eventId = this.value;
            const ticketSelect = document.getElementById('ticket_id');
            
            if (eventId) {
                // Clear existing options
                ticketSelect.innerHTML = '<option value="">Loading tickets...</option>';
                ticketSelect.disabled = true;
                
                // Fetch tickets for the selected event
                fetch(`/events/${eventId}/tickets`)
                    .then(response => response.json())
                    .then(data => {
                        ticketSelect.innerHTML = '<option value="">Select a ticket type</option>';
                        
                        data.forEach(ticket => {
                            const option = document.createElement('option');
                            option.value = ticket.id;
                            option.textContent = `${ticket.name} - $${parseFloat(ticket.price).toFixed(2)} (${ticket.event_zone ? ticket.event_zone.name : 'General'})`;
                            
                            // Check if this was the previously selected value
                            if ('{{ old('ticket_id') }}' == ticket.id) {
                                option.selected = true;
                            }
                            
                            ticketSelect.appendChild(option);
                        });
                        
                        ticketSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching tickets:', error);
                        ticketSelect.innerHTML = '<option value="">Error loading tickets</option>';
                        ticketSelect.disabled = false;
                    });
            } else {
                ticketSelect.innerHTML = '<option value="">Select an event first</option>';
                ticketSelect.disabled = true;
            }
        });

        // Trigger the change event on page load if there's an old value
        document.addEventListener('DOMContentLoaded', function() {
            const eventSelect = document.getElementById('event_id');
            if (eventSelect.value) {
                eventSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
