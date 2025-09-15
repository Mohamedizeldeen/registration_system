@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Payment Details</h1>
                <p class="text-gray-600">Payment ID: #{{ $payment->id }}</p>
            </div>
            
            <div class="flex space-x-2">
                <a href="{{ route('company-admin.payments') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Payments
                </a>
                
                @if($payment->status === 'completed')
                <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-check mr-2"></i>
                    Completed
                </button>
                @elseif($payment->status === 'pending')
                <button class="bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-clock mr-2"></i>
                    Pending
                </button>
                @else
                <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    {{ ucfirst($payment->status) }}
                </button>
                @endif
            </div>
        </div>

        <!-- Payment Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Payment Details -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h2>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Amount:</span>
                        <span class="font-medium">
                            {{ $payment->currency->code ?? 'USD' }} {{ number_format($payment->amount, 2) }}
                        </span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-medium">
                            @if($payment->status === 'completed')
                                <span class="text-green-600">Completed</span>
                            @elseif($payment->status === 'pending')
                                <span class="text-yellow-600">Pending</span>
                            @else
                                <span class="text-red-600">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </span>
                    </div>
                    
                    @if($payment->payment_method)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Method:</span>
                        <span class="font-medium">{{ ucfirst($payment->payment_method) }}</span>
                    </div>
                    @endif
                    
                    @if($payment->transaction_id)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Transaction ID:</span>
                        <span class="font-medium font-mono text-sm">{{ $payment->transaction_id }}</span>
                    </div>
                    @endif
                    
                    <div class="flex justify-between">
                        <span class="text-gray-600">Payment Date:</span>
                        <span class="font-medium">{{ $payment->created_at->format('M d, Y H:i') }}</span>
                    </div>
                    
                    @if($payment->completed_at)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Completed At:</span>
                        <span class="font-medium">{{ $payment->completed_at->format('M d, Y H:i') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Event & Attendee Information -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Event & Attendee</h2>
                
                @if($payment->event)
                <div class="mb-4 p-4 bg-white rounded-lg border">
                    <h3 class="font-medium text-gray-900 mb-2">
                        <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                        {{ $payment->event->name }}
                    </h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        @if($payment->event->start_date)
                        <div>
                            <i class="fas fa-clock mr-2"></i>
                            {{ $payment->event->start_date->format('M d, Y H:i') }}
                        </div>
                        @endif
                        @if($payment->event->location)
                        <div>
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $payment->event->location }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                @if($payment->attendee)
                <div class="p-4 bg-white rounded-lg border">
                    <h3 class="font-medium text-gray-900 mb-2">
                        <i class="fas fa-user mr-2 text-green-600"></i>
                        {{ $payment->attendee->full_name }}
                    </h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <div>
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $payment->attendee->email }}
                        </div>
                        @if($payment->attendee->phone)
                        <div>
                            <i class="fas fa-phone mr-2"></i>
                            {{ $payment->attendee->phone }}
                        </div>
                        @endif
                        @if($payment->attendee->company)
                        <div>
                            <i class="fas fa-building mr-2"></i>
                            {{ $payment->attendee->company }}
                        </div>
                        @endif
                        <div class="mt-2">
                            @if($payment->attendee->checked_in)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Checked In
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending Check-in
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Ticket Information -->
        @if($payment->attendee && $payment->attendee->ticket)
        <div class="mt-6 bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Ticket Information</h2>
            
            <div class="bg-white rounded-lg border p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <h4 class="font-medium text-gray-900">Ticket Type</h4>
                        <p class="text-gray-600">{{ $payment->attendee->ticket->name }}</p>
                    </div>
                    
                    @if($payment->attendee->ticket->eventZone)
                    <div>
                        <h4 class="font-medium text-gray-900">Zone</h4>
                        <p class="text-gray-600">{{ $payment->attendee->ticket->eventZone->name }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <h4 class="font-medium text-gray-900">Price</h4>
                        <p class="text-gray-600">
                            {{ $payment->attendee->ticket->currency->code ?? 'USD' }} 
                            {{ number_format($payment->attendee->ticket->price, 2) }}
                        </p>
                    </div>
                </div>
                
                @if($payment->attendee->ticket->description)
                <div class="mt-4">
                    <h4 class="font-medium text-gray-900">Description</h4>
                    <p class="text-gray-600">{{ $payment->attendee->ticket->description }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="mt-6 flex space-x-4">
            @if($payment->attendee)
            <a href="{{ route('company-admin.quick-print', $payment->attendee->id) }}" 
               class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center"
               target="_blank">
                <i class="fas fa-print mr-2"></i>
                Print Ticket
            </a>
            @endif
            
            @if($payment->status === 'pending')
            <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-check mr-2"></i>
                Mark as Completed
            </button>
            @endif
        </div>

        <!-- Notes/Additional Information -->
        @if($payment->notes || $payment->reference_number)
        <div class="mt-6 bg-gray-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Additional Information</h2>
            
            @if($payment->reference_number)
            <div class="mb-3">
                <h4 class="font-medium text-gray-900">Reference Number</h4>
                <p class="text-gray-600 font-mono">{{ $payment->reference_number }}</p>
            </div>
            @endif
            
            @if($payment->notes)
            <div>
                <h4 class="font-medium text-gray-900">Notes</h4>
                <p class="text-gray-600">{{ $payment->notes }}</p>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>
@endsection
