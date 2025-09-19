@extends('layouts.app')

@section('title', 'QR Check-in')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Success/Info Message -->
        <div class="text-center mb-6">
            @if($status === 'success')
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-green-600 mb-2">Check-in Successful!</h1>
            @else
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-blue-600 mb-2">Already Checked In</h1>
            @endif
            
            <p class="text-gray-600">{{ $message }}</p>
        </div>

        <!-- Attendee Information -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">Attendee Details</h2>
            
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Name:</span>
                    <span class="text-gray-900">{{ $attendee->full_name }}</span>
                </div>
                
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Email:</span>
                    <span class="text-gray-900">{{ $attendee->email }}</span>
                </div>
                
                @if($attendee->event)
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Event:</span>
                    <span class="text-gray-900">{{ $attendee->event->name }}</span>
                </div>
                @endif
                
                @if($attendee->ticket)
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Ticket:</span>
                    <span class="text-gray-900">{{ $attendee->ticket->name }}</span>
                </div>
                @endif
                
                <div class="flex justify-between">
                    <span class="font-medium text-gray-600">Check-in Time:</span>
                    <span class="text-gray-900">{{ $attendee->checked_in_at->format('M d, Y g:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="text-center">
            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                <i class="fas fa-check mr-2"></i>
                Checked In
            </span>
        </div>
    </div>
</div>
@endsection
