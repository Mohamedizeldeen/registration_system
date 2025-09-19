@extends('layouts.app')

@section('title', 'Company Attendees')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Attendees</h1>
                <p class="text-gray-600">Manage attendees for {{ auth()->user()->company->name ?? 'your company' }} events</p>
            </div>
            
            <!-- Export Button -->
            <div class="flex gap-2">
                <a href="{{ route('company-admin.print-tickets.form') }}{{ request('event_id') ? '?event_id=' . request('event_id') : '' }}" 
                   class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-print mr-2"></i>
                    Print Tickets
                </a>
                <a href="{{ route('company-admin.attendees') }}?export=csv{{ request('event_id') ? '&event_id=' . request('event_id') : '' }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Export CSV
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('company-admin.attendees') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-64">
                    <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Event</label>
                    <select name="event_id" id="event_id" class="w-full border-gray-300 rounded-lg">
                        <option value="">All Events</option>
                        @foreach(auth()->user()->company->events as $event)
                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 min-w-64">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded-lg">
                        <option value="">All Statuses</option>
                        <option value="checked_in" {{ request('status') == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                        <option value="not_checked_in" {{ request('status') == 'not_checked_in' ? 'selected' : '' }}>Not Checked In</option>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Attendees Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($attendees as $attendee)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('company-admin.attendees.show', $attendee->id) }}" class="text-sm font-medium text-blue-600 hover:text-blue-900 hover:underline">
                                    {{ $attendee->first_name }} {{ $attendee->last_name }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attendee->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attendee->event->name }}</div>
                                <div class="text-xs text-gray-500">{{ $attendee->event->event_date ? \Carbon\Carbon::parse($attendee->event->event_date)->format('M d, Y') : '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $attendee->ticket->name ?? 'N/A' }}</div>
                                @if($attendee->ticket)
                                    <div class="text-xs text-gray-500">{{ $attendee->ticket->price }} {{ $attendee->ticket->currency->code ?? '' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendee->checked_in)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Checked In
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($attendee->payment)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Paid
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Unpaid
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    @if(!$attendee->checked_in)
                                        <form method="POST" action="{{ route('attendees.checkin', $attendee) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900">
                                                <i class="fas fa-check mr-1"></i>Check In
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <a href="{{ route('company-admin.attendees.show', $attendee) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                    
                                    <a href="{{ route('company-admin.quick-print', $attendee->id) }}" class="text-purple-600 hover:text-purple-900" target="_blank" title="Print Ticket">
                                        <i class="fas fa-print mr-1"></i>Print
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                No attendees found for your company events.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $attendees->links() }}
        </div>
    </div>
</div>
@endsection
