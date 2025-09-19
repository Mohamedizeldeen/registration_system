@extends('layouts.app')

@section('title', 'Coupons')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Discount Coupons</h1>
                    <p class="text-gray-600 mt-2">Manage promotional coupons and discounts</p>
                </div>
                <a href="{{ route('coupons.create') }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Create New Coupon
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        @if(isset($coupons) && $coupons->count() > 0)
            <!-- Coupons Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($coupons as $coupon)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div class="bg-gradient-to-r from-green-500 to-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    {{ $coupon->code }}
                                </div>
                                <span class="text-2xl font-bold text-green-600">{{ $coupon->discount }}%</span>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-ticket-alt mr-2 text-blue-500"></i>
                                    <span>{{ $coupon->ticket->name ?? 'General' }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-calendar mr-2 text-red-500"></i>
                                    <span>Expires: {{ date('M d, Y', strtotime($coupon->expiry_date)) }}</span>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-users mr-2 text-purple-500"></i>
                                    <span>Used: {{ $coupon->usage_count }}/{{ $coupon->max_usage }}</span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-sm">
                                    @if($coupon->expiry_date < now())
                                        <span class="text-red-600 font-semibold">Expired</span>
                                    @elseif($coupon->usage_count >= $coupon->max_usage)
                                        <span class="text-orange-600 font-semibold">Limit Reached</span>
                                    @else
                                        <span class="text-green-600 font-semibold">Active</span>
                                    @endif
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('coupons.show', $coupon->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-tags text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Coupons Found</h3>
                <p class="text-gray-500 mb-6">Start creating discount coupons to boost your event sales.</p>
                <a href="{{ route('coupons.create') }}" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md transition duration-200">
                    Create Your First Coupon
                </a>
            </div>
        @endif
    </div>
@endsection
