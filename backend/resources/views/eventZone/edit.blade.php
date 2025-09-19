@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('eventZones.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Edit Event Zone</h1>
                        <p class="text-gray-600 mt-2">Update zone configuration and settings</p>
                    </div>
                </div>
                <a href="{{ route('eventZones.show', $eventZone->id ?? 1) }}" class="text-blue-600 hover:text-blue-800">
                    <i class="fas fa-eye mr-2"></i>View Zone
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md">
            <!-- Current Zone Info -->
            <div class="border-b border-gray-200 px-6 py-4 bg-gradient-to-r from-purple-50 to-blue-50">
                <div class="flex items-center">
                    <div
                        class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ $eventZone->name ?? 'VIP Section' }}
                        </h2>
                        <p class="text-gray-600">
                            Current capacity: {{ number_format($eventZone->capacity ?? 100) }} •
                            Event: {{ $eventZone->event->name ?? 'Tech Conference 2024' }}
                        </p>
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
            <form action="{{ route('eventZones.update', $eventZone->id ?? 1) }}" method="POST" class="p-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Zone Name -->
                    <div class="md:col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-purple-500"></i>Zone Name *
                        </label>
                        <input type="text" id="name" name="name"
                            value="{{ old('name', $eventZone->name ?? 'VIP Section') }}" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="e.g., VIP Section, General Admission">
                    </div>

                    <!-- Capacity -->
                    <div class="md:col-span-1">
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-2 text-blue-500"></i>Capacity *
                        </label>
                        <input type="number" id="capacity" name="capacity"
                            value="{{ old('capacity', $eventZone->capacity ?? 100) }}" required min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            placeholder="Maximum number of attendees">
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-info-circle mr-1"></i>
                            Current occupancy: {{ $eventZone->tickets_sold ?? 45 }}/{{ $eventZone->capacity ?? 100 }}
                        </p>
                    </div>
                </div>

                <!-- Event Selection -->
                <div class="mt-6">
                    <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-green-500"></i>Associated Event *
                    </label>
                    <select id="event_id" name="event_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select an event</option>
                        @if(isset($events))
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ old('event_id', $eventZone->event_id ?? 1) == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }} - {{ date('M d, Y', strtotime($event->event_date)) }}
                                </option>
                            @endforeach
                        @else
                            <!-- Sample options for demo -->
                            <option value="1" {{ old('event_id', $eventZone->event_id ?? 1) == 1 ? 'selected' : '' }}>
                                Tech Conference 2024 - Jan 15, 2024
                            </option>
                            <option value="2" {{ old('event_id', $eventZone->event_id ?? 1) == 2 ? 'selected' : '' }}>
                                Music Festival Summer - Jul 20, 2024
                            </option>
                            <option value="3" {{ old('event_id', $eventZone->event_id ?? 1) == 3 ? 'selected' : '' }}>
                                Business Summit - Mar 10, 2024
                            </option>
                        @endif
                    </select>
                </div>


                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-orange-500"></i>Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        placeholder="Describe the zone location, amenities, or special features...">{{ old('description', $eventZone->description ?? 'Premium seating area with exclusive access to VIP lounge and complimentary refreshments.') }}</textarea>
                </div>


                <!-- Capacity Change Warning -->
                <div id="capacity-warning" class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg hidden">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                        <div>
                            <h4 class="font-semibold text-yellow-800">Capacity Change Warning</h4>
                            <p class="text-yellow-700 text-sm mt-1">
                                Reducing capacity below current ticket sales may cause issues.
                                Please ensure the new capacity accommodates existing reservations.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('eventZones.index') }}"
                        class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <div class="flex space-x-3">
                        <a href="{{ route('eventZones.show', $eventZone->id ?? 1) }}"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition duration-200">
                            <i class="fas fa-eye mr-2"></i>View Zone
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200">
                            <i class="fas fa-save mr-2"></i>Update Zone
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Capacity change warning
        const capacityInput = document.getElementById('capacity');
        const warningDiv = document.getElementById('capacity-warning');
        const currentSold = {{ $eventZone->tickets_sold ?? 45 }};

        capacityInput.addEventListener('input', function () {
            const newCapacity = parseInt(this.value);
            if (newCapacity < currentSold) {
                warningDiv.classList.remove('hidden');
            } else {
                warningDiv.classList.add('hidden');
            }
        });
    </script>
@endsection