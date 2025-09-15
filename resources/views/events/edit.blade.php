@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="{{ route('events.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Event</h1>
                    <p class="text-gray-600 mt-2">Update the details for "{{ $event->name }}"</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Info Section -->
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-6 mb-8">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-amber-900">Event Editing Guidelines</h3>
                    <div class="mt-2 text-amber-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Changes to event date/time may affect existing bookings</li>
                            <li>Venue changes should be communicated to attendees</li>
                            <li>Review all details before saving changes</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Event Details</h2>
            </div>
            
            <form class="p-6 space-y-6" method="POST" action="{{ route('events.update', $event->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <!-- Event Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Event Name *</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $event->name) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>{{ old('description', $event->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Event Type *</label>
                    <select id="type" name="type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        <option value="">Select Event Type</option>
                        <option value="hybrid" {{ old('type', $event->type) == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        <option value="virtual" {{ old('type', $event->type) == 'virtual' ? 'selected' : '' }}>Virtual</option>
                        <option value="onsite" {{ old('type', $event->type) == 'onsite' ? 'selected' : '' }}>Onsite</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Logo Upload -->
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-2">Event Logo</label>
                    @if($event->logo)
                        <div class="mb-3">
                            <img src="{{ asset('storage/logos/' . $event->logo) }}" alt="Current Logo" class="h-16 w-16 object-cover rounded-md">
                            <p class="text-sm text-gray-500 mt-1">Current logo</p>
                        </div>
                    @endif
                    <input type="file" id="logo" name="logo" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">Upload a new logo to replace the current one (optional)</p>
                    @error('logo')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Event Date *</label>
                        <input type="date" id="event_date" name="event_date" value="{{ old('event_date', $event->event_date) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('event_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                        <input type="time" id="start_time" name="start_time" value="{{ old('start_time', $event->start_time) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('start_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time *</label>
                        <input type="time" id="end_time" name="end_time" value="{{ old('end_time', $event->end_time) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        @error('end_time')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Banner URL -->
                <div>
                    <label for="banner_url" class="block text-sm font-medium text-gray-700 mb-2">Banner URL</label>
                    <input type="url" id="banner_url" name="banner_url" value="{{ old('banner_url', $event->banner_url) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter banner URL">
                    @error('banner_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $event->email) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter email">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $event->phone) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter phone number">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                    <input type="text" id="location" name="location" value="{{ old('location', $event->location) }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Enter event location">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Social Media -->
                <div>
                    <label for="social_media" class="block text-sm font-medium text-gray-700 mb-2">Social Media Platform</label>
                    <select id="social_media" name="social_media" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select Social Media Platform</option>
                        <option value="facebook" {{ old('social_media', $event->social_media) == 'facebook' ? 'selected' : '' }}>Facebook</option>
                        <option value="instagram" {{ old('social_media', $event->social_media) == 'instagram' ? 'selected' : '' }}>Instagram</option>
                        <option value="linkedin" {{ old('social_media', $event->social_media) == 'linkedin' ? 'selected' : '' }}>LinkedIn</option>
                        <option value="website" {{ old('social_media', $event->social_media) == 'website' ? 'selected' : '' }}>Website</option>
                    </select>
                    @error('social_media')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Stats (Read-only) -->
                @if($event->tickets->count() > 0 || $event->attendees->count() > 0)
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">Event Statistics</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-blue-600">{{ $event->event_zones_count ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Zones</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-green-600">{{ $event->tickets_count ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Ticket Types</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-purple-600">{{ $event->attendees_count ?? 0 }}</p>
                            <p class="text-sm text-gray-600">Attendees</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-orange-600">${{ number_format($event->tickets->sum('price') ?? 0, 2) }}</p>
                            <p class="text-sm text-gray-600">Total Value</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('events.show', $event->id) }}" class="px-4 py-2 text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-md transition duration-200">
                        <i class="fas fa-eye mr-2"></i>View Event
                    </a>
                    <div class="flex space-x-3">
                        <a href="{{ route('events.index') }}" class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition duration-200">Cancel</a>
                        <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200">
                            <i class="fas fa-save mr-2"></i>Update Event
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
