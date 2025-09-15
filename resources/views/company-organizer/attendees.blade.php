@extends('layouts.app')

@section('title', 'Attendees - Company Organizer')

@section('content')
    <main class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Event Attendees</h2>
            <p class="text-gray-600">View and manage attendee check-ins for company events</p>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-8">
            <form method="GET" action="{{ route('company-organizer.attendees') }}" class="flex items-end gap-4">
                <div class="flex-1">
                    <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">Filter by Event</label>
                    <select name="event_id" id="event_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All Events</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }} - {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Attendees List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">
                    Attendees 
                    @if(request('event_id'))
                        for {{ $events->firstWhere('id', request('event_id'))->name ?? 'Selected Event' }}
                    @endif
                    ({{ $attendees->total() }} total)
                </h3>
                @if(request('event_id'))
                    <a href="{{ route('company-organizer.print-tickets', ['event_id' => request('event_id')]) }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Print Tickets
                    </a>
                @endif
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Name</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Email</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Company</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Event</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Ticket</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Check-in Status</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Check-in Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendees as $attendee)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="py-4">
                                    <a href="{{ route('company-organizer.attendees.show', $attendee->id) }}" class="font-medium text-blue-600 hover:text-blue-900 hover:underline">
                                        {{ $attendee->first_name }} {{ $attendee->last_name }}
                                    </a>
                                    <div class="text-sm text-gray-500">{{ $attendee->job_title ?? 'N/A' }}</div>
                                </td>
                                <td class="py-4 text-gray-900">{{ $attendee->email }}</td>
                                <td class="py-4">
                                    <div class="text-gray-900">{{ $attendee->company ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $attendee->country ?? 'N/A' }}</div>
                                </td>
                                <td class="py-4 text-gray-900">{{ $attendee->event->name ?? 'N/A' }}</td>
                                <td class="py-4">
                                    <div class="text-gray-900">{{ $attendee->ticket->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">${{ number_format($attendee->ticket->price ?? 0, 2) }}</div>
                                </td>
                                <td class="py-4">
                                    @if($attendee->checked_in)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Checked In
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Not Checked In
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 text-gray-900">
                                    {{ $attendee->checked_in_at ? \Carbon\Carbon::parse($attendee->checked_in_at)->format('M d, Y g:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-500">No attendees found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($attendees->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $attendees->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Summary Stats -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-gray-900">{{ $attendees->total() }}</div>
                    <div class="text-sm text-gray-600">Total Attendees</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $attendees->where('checked_in', true)->count() }}</div>
                    <div class="text-sm text-gray-600">Checked In</div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-red-600">{{ $attendees->where('checked_in', false)->count() }}</div>
                    <div class="text-sm text-gray-600">Not Checked In</div>
                </div>
            </div>
        </div>
    </main>
@endsection
