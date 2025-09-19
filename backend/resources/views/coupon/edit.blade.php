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
                    <h1 class="text-3xl font-bold text-gray-900">Edit Coupon</h1>
                    <p class="text-gray-600 mt-2">Update coupon details</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('coupons.update', $coupon->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Coupon Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2"></i>Coupon Code
                        </label>
                        <input type="text" id="code" name="code" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                            value="{{ old('code', $coupon->code) }}">
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
                            value="{{ old('discount', $coupon->discount) }}">
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
                                    <option value="{{ $ticket->id }}" 
                                        {{ old('ticket_id', $coupon->ticket_id) == $ticket->id ? 'selected' : '' }}>
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
                            value="{{ old('expiry_date', $coupon->expiry_date) }}">
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
                            value="{{ old('max_usage', $coupon->max_usage) }}">
                        @error('max_usage')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Usage (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-chart-line mr-2"></i>Current Usage
                        </label>
                        <div class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-md text-gray-600">
                            {{ $coupon->usage_count }} times used
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Current Status</h3>
                    <div class="flex items-center space-x-4">
                        @if($coupon->expiry_date < now())
                            <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">Expired</span>
                        @elseif($coupon->usage_count >= $coupon->max_usage)
                            <span class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold">Usage Limit Reached</span>
                        @else
                            <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Active</span>
                        @endif
                        <span class="text-gray-600">Created {{ $coupon->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('coupons.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>Update Coupon
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
