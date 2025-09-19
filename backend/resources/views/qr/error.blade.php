@extends('layouts.app')

@section('title', 'QR Check-in Error')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <!-- Error Message -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-red-600 mb-2">Check-in Failed</h1>
            <p class="text-gray-600">{{ $message }}</p>
        </div>

        <!-- Help Information -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-3">What to do next?</h2>
            
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start">
                    <i class="fas fa-circle text-gray-400 text-xs mt-2 mr-2"></i>
                    <span>Make sure you're scanning the correct QR code from your event ticket</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-circle text-gray-400 text-xs mt-2 mr-2"></i>
                    <span>Contact event staff if you continue to experience issues</span>
                </li>
                <li class="flex items-start">
                    <i class="fas fa-circle text-gray-400 text-xs mt-2 mr-2"></i>
                    <span>Have your ticket ID ready: it's the number shown below the QR code</span>
                </li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="text-center space-y-3">
            <button onclick="history.back()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg">
                <i class="fas fa-arrow-left mr-2"></i>
                Go Back
            </button>
        </div>
    </div>
</div>
@endsection
