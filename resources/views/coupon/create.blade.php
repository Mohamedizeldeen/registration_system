@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="{{ route('coupons.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Coupon</h1>
                    <p class="text-gray-600 mt-2">Set up a new discount coupon for your events</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('coupons.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Coupon Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2"></i>Coupon Code
                        </label>
                        <input type="text" id="code" name="code" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="e.g., SAVE20, EARLY50"
                            value="{{ old('code') }}">
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Percentage -->
                    <div>
                        <label for="discount" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-percent mr-2"></i>Discount Percentage
                        </label>
                        <input type="number" id="discount" name="discount" required min="1" max="100"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="e.g., 20"
                            value="{{ old('discount') }}">
                        @error('discount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ticket -->
                    <div>
                        <label for="ticket_id" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-ticket-alt mr-2"></i>Applicable Ticket
                        </label>
                        <select id="ticket_id" name="ticket_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <option value="">Select a ticket</option>
                            @if(isset($tickets))
                                @foreach($tickets as $ticket)
                                    <option value="{{ $ticket->id }}" {{ old('ticket_id') == $ticket->id ? 'selected' : '' }}>
                                        {{ $ticket->name }} - ${{ $ticket->price }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('ticket_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expiry Date -->
                    <div>
                        <label for="expiry_date" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar mr-2"></i>Expiry Date
                        </label>
                        <input type="date" id="expiry_date" name="expiry_date" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            value="{{ old('expiry_date') }}"
                            min="{{ date('Y-m-d') }}">
                        @error('expiry_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Usage -->
                    <div>
                        <label for="max_usage" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-2"></i>Maximum Usage
                        </label>
                        <input type="number" id="max_usage" name="max_usage" required min="1"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            placeholder="e.g., 100"
                            value="{{ old('max_usage', 1) }}">
                        @error('max_usage')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Usage Count (Hidden field, default 0) -->
                    <input type="hidden" name="usage_count" value="0">
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('coupons.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>Create Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
