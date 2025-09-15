@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="{{ route('eventZones.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create Event Zone</h1>
                    <p class="text-gray-600 mt-2">Set up a new seating area or capacity zone for your event</p>
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
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-600 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-map-marker-alt text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">Zone Information</h2>
                        <p class="text-gray-600">Configure zone details and capacity</p>
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
            <form action="{{ route('eventZones.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Zone Name -->
                    <div class="md:col-span-1">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2 text-purple-500"></i>Zone Name *
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="e.g., VIP Section, General Admission">
                    </div>

                    <!-- Capacity -->
                    <div class="md:col-span-1">
                        <label for="capacity" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-2 text-blue-500"></i>Capacity *
                        </label>
                        <input type="number" id="capacity" name="capacity" value="{{ old('capacity') }}" required min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                               placeholder="Maximum number of attendees">
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

                <!-- Zone Type (Optional) -->
                <div class="mt-6">
                    <label for="zone_type" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-layer-group mr-2 text-indigo-500"></i>Zone Type
                    </label>
                    <select id="zone_type" name="zone_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Select zone type</option>
                        <option value="vip" {{ old('zone_type') == 'vip' ? 'selected' : '' }}>VIP</option>
                        <option value="premium" {{ old('zone_type') == 'premium' ? 'selected' : '' }}>Premium</option>
                        <option value="standard" {{ old('zone_type') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="general" {{ old('zone_type') == 'general' ? 'selected' : '' }}>General Admission</option>
                        <option value="balcony" {{ old('zone_type') == 'balcony' ? 'selected' : '' }}>Balcony</option>
                        <option value="floor" {{ old('zone_type') == 'floor' ? 'selected' : '' }}>Floor</option>
                        <option value="mezzanine" {{ old('zone_type') == 'mezzanine' ? 'selected' : '' }}>Mezzanine</option>
                    </select>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2 text-orange-500"></i>Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                              placeholder="Describe the zone location, amenities, or special features...">{{ old('description') }}</textarea>
                </div>

                <!-- Quick Zone Templates -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">
                        <i class="fas fa-magic mr-2"></i>Quick Templates
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <button type="button" class="template-btn px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                data-name="VIP Section" data-capacity="50" data-type="vip" data-description="Premium seating with exclusive amenities">
                            VIP Section
                        </button>
                        <button type="button" class="template-btn px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                data-name="General Admission" data-capacity="500" data-type="general" data-description="Standard admission area">
                            General Admission
                        </button>
                        <button type="button" class="template-btn px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                data-name="Balcony Seating" data-capacity="100" data-type="balcony" data-description="Elevated seating with great views">
                            Balcony
                        </button>
                        <button type="button" class="template-btn px-3 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                                data-name="Floor Section" data-capacity="300" data-type="floor" data-description="Ground level seating">
                            Floor Section
                        </button>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('eventZones.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 transition duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>Create Zone
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Template functionality
        document.querySelectorAll('.template-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('name').value = this.dataset.name;
                document.getElementById('capacity').value = this.dataset.capacity;
                document.getElementById('zone_type').value = this.dataset.type;
                document.getElementById('description').value = this.dataset.description;
                
                // Visual feedback
                this.classList.add('bg-purple-100', 'border-purple-300');
                setTimeout(() => {
                    this.classList.remove('bg-purple-100', 'border-purple-300');
                }, 1000);
            });
        });
    </script>
@endsection
