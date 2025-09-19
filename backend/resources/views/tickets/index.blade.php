@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tickets</h1>
                <p class="text-gray-600">Manage event tickets</p>
            </div>
            
            @if(auth()->user()->role === 'super_admin')
                <a href="{{ route('tickets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Create Ticket
                </a>
            @endif
        </div>

        <!-- Tickets Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ticket Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin' || auth()->user()->role === 'company_organizer')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($tickets as $ticket)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->description ?? 'No description' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ticket->eventZone->event->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->eventZone->event->event_date ? \Carbon\Carbon::parse($ticket->eventZone->event->event_date)->format('M d, Y') : 'No date' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ticket->eventZone->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->eventZone->description ?? '' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $ticket->price }} {{ $ticket->currency->code ?? 'USD' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ticket->available_quantity ?? 'Unlimited' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $ticket->total_quantity ?? 'Unlimited' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($ticket->available_quantity > 0 || $ticket->available_quantity === null)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Sold Out
                                    </span>
                                @endif
                            </td>
                            @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin' || auth()->user()->role === 'company_admin' || auth()->user()->role === 'company_organizer')
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        @if(auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin')
                                            <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        @elseif(auth()->user()->role === 'company_admin')
                                            <a href="{{ route('company-admin.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        @elseif(auth()->user()->role === 'company_organizer')
                                            <a href="{{ route('company-organizer.tickets.show', $ticket) }}" class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-eye mr-1"></i>View
                                            </a>
                                        @endif
                                        @if(auth()->user()->role === 'super_admin')
                                            <a href="{{ route('tickets.edit', $ticket) }}" class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit mr-1"></i>Edit
                                            </a>
                                            <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" class="inline" 
                                                  onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i class="fas fa-trash mr-1"></i>Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                @if(auth()->user()->role === 'super_admin')
                                    No tickets found. <a href="{{ route('tickets.create') }}" class="text-blue-600 hover:text-blue-800">Create the first ticket</a>.
                                @else
                                    No tickets found for your company events.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($tickets->hasPages())
            <div class="mt-6">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
