@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payment Details</h1>
                <p class="text-gray-600">View payment information and transaction details</p>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ url()->previous() }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back
                </a>
                
                @if(auth()->user()->role === 'super_admin')
                    <a href="{{ route('payments.edit', $payment) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                @endif
            </div>
        </div>

        <!-- Payment Status -->
        <div class="mb-6">
            @if($payment->status === 'completed')
                <div class="bg-green-50 border-l-4 border-green-400 p-4">
                    <div class="flex">
                        <i class="fas fa-check-circle text-green-400 text-xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-green-800">Payment Completed</h3>
                            <p class="text-green-700">This payment has been successfully processed.</p>
                        </div>
                    </div>
                </div>
            @elseif($payment->status === 'pending')
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                    <div class="flex">
                        <i class="fas fa-clock text-yellow-400 text-xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-yellow-800">Payment Pending</h3>
                            <p class="text-yellow-700">This payment is awaiting processing.</p>
                        </div>
                    </div>
                </div>
            @elseif($payment->status === 'failed')
                <div class="bg-red-50 border-l-4 border-red-400 p-4">
                    <div class="flex">
                        <i class="fas fa-times-circle text-red-400 text-xl mr-3"></i>
                        <div>
                            <h3 class="text-lg font-medium text-red-800">Payment Failed</h3>
                            <p class="text-red-700">This payment could not be processed.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Payment Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Payment Details -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-credit-card mr-2 text-blue-600"></i>
                    Payment Information
                </h2>
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment ID</label>
                            <p class="mt-1 text-sm text-gray-900">#{{ $payment->id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Transaction ID</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->transaction_id ?? 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Amount</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">
                                {{ $payment->currency ?? 'USD' }} {{ number_format($payment->amount, 2) }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($payment->payment_method ?? 'N/A') }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Payment Date</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $payment->payment_date ? $payment->payment_date->format('M d, Y H:i') : $payment->created_at->format('M d, Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <p class="mt-1">
                                @if($payment->status === 'completed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Completed
                                    </span>
                                @elseif($payment->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @elseif($payment->status === 'failed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Failed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        {{ ucfirst($payment->status ?? 'Unknown') }}
                                    </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendee Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user mr-2 text-green-600"></i>
                    Attendee Information
                </h2>
                
                @if($payment->attendee)
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Full Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->attendee->full_name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $payment->attendee->email }}</p>
                        </div>
                        
                        @if($payment->attendee->phone)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $payment->attendee->phone }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Check-in Status</label>
                            <p class="mt-1">
                                @if($payment->attendee->checked_in)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Checked In
                                    </span>
                                    @if($payment->attendee->checked_in_at)
                                        <span class="text-xs text-gray-500 block mt-1">
                                            {{ $payment->attendee->checked_in_at->format('M d, Y H:i') }}
                                        </span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Not Checked In
                                    </span>
                                @endif
                            </p>
                        </div>
                        
                        <div class="pt-2">
                            <a href="{{ route('attendees.show', $payment->attendee) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                <i class="fas fa-external-link-alt mr-1"></i>View Attendee Details
                            </a>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500">No attendee information available.</p>
                @endif
            </div>
        </div>

        <!-- Event Information -->
        @if($payment->attendee && $payment->attendee->event)
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-purple-600"></i>
                    Event Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Event Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->attendee->event->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Start Date</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $payment->attendee->event->start_date ? $payment->attendee->event->start_date->format('M d, Y H:i') : 'N/A' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Location</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->attendee->event->location ?? 'N/A' }}</p>
                    </div>
                </div>
                
                @if($payment->attendee->event->description)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ Str::limit($payment->attendee->event->description, 200) }}</p>
                    </div>
                @endif
                
                <div class="mt-4">
                    <a href="{{ route('events.show', $payment->attendee->event) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-external-link-alt mr-1"></i>View Event Details
                    </a>
                </div>
            </div>
        @endif

        <!-- Ticket Information -->
        @if($payment->ticket)
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-ticket-alt mr-2 text-orange-600"></i>
                    Ticket Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ticket Type</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->ticket->name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <p class="mt-1 text-sm text-gray-900">
                            {{ $payment->ticket->price }} {{ $payment->ticket->currency->code ?? 'USD' }}
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Zone</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->ticket->eventZone->name ?? 'N/A' }}</p>
                    </div>
                </div>
                
                @if($payment->ticket->description)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $payment->ticket->description }}</p>
                    </div>
                @endif
                
                <div class="mt-4">
                    <a href="{{ route('tickets.show', $payment->ticket) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                        <i class="fas fa-external-link-alt mr-1"></i>View Ticket Details
                    </a>
                </div>
            </div>
        @endif

        <!-- Activity Log -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-history mr-2 text-gray-600"></i>
                Payment Timeline
            </h2>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-plus-circle text-blue-500 mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Payment Created</p>
                        <p class="text-xs text-gray-500">{{ $payment->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
                
                @if($payment->payment_date && $payment->payment_date != $payment->created_at)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            @if($payment->status === 'completed')
                                <i class="fas fa-check-circle text-green-500 mt-1"></i>
                            @elseif($payment->status === 'failed')
                                <i class="fas fa-times-circle text-red-500 mt-1"></i>
                            @else
                                <i class="fas fa-clock text-yellow-500 mt-1"></i>
                            @endif
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Payment {{ ucfirst($payment->status) }}</p>
                            <p class="text-xs text-gray-500">{{ $payment->payment_date->format('M d, Y H:i:s') }}</p>
                        </div>
                    </div>
                @endif
                
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-edit text-gray-400 mt-1"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900">Last Updated</p>
                        <p class="text-xs text-gray-500">{{ $payment->updated_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
