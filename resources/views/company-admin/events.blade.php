@extends('layouts.app')

@section('title', 'Company Events')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Events</h1>
                <p class="text-gray-600">Manage events for {{ auth()->user()->company->name ?? 'your company' }}</p>
            </div>
            
            <a href="{{ route('events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center hidden">
                <i class="fas fa-plus mr-2"></i>
                Create Event
            </a>
        </div>

        <!-- Events Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($events as $event)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <!-- Event Image Placeholder -->
                    <div class="h-48 rounded-t-lg overflow-hidden">
                        <img src="{{ asset($event->banner_url) }}" alt="{{ $event->name }}" class="w-full h-full object-cover">
                    </div>
                    
                    <!-- Event Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $event->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4">{{ Str::limit($event->description, 100) }}</p>
                        
                        <!-- Event Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                <span>{{ $event->event_date?->format('M d, Y') }} - {{ $event->event_end_date }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                <span>{{ $event->location ?? 'Location TBD' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-600">
                                <i class="fas fa-users mr-2 text-green-500"></i>
                                <span>{{ $event->attendees_count ?? $event->attendees->count() }} attendees</span>
                            </div>
                        </div>
                        
                        <!-- Event Status -->
                        <div class="mb-4">
                            @if($event->start_date && $event->start_date->isFuture())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-clock mr-1"></i>Upcoming
                                </span>
                            @elseif($event->start_date && $event->end_date && $event->start_date->isPast() && $event->end_date->isFuture())
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-play mr-1"></i>Live
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-check mr-1"></i>Completed
                                </span>
                            @endif
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <a href="{{ route('events.show', $event) }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            <a href="{{ route('events.edit', $event) }}" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white text-center py-2 px-3 rounded-lg text-sm font-medium">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <i class="fas fa-calendar-alt text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No events found</h3>
                        <p class="text-gray-600 mb-4">Get started by creating your first event for your company.</p>
                        <a href="{{ route('events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Create Event
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($events->hasPages())
            <div class="mt-8">
                {{ $events->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
