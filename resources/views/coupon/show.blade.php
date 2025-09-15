<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coupon Details - MFW Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('coupons.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Coupon Details</h1>
                        <p class="text-gray-600 mt-2">View coupon information and statistics</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('coupons.edit', $coupon->id) }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this coupon?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition duration-200 flex items-center">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <!-- Coupon Overview Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-green-500 to-blue-600 px-8 py-6">
                <div class="flex justify-between items-center text-white">
                    <div>
                        <h2 class="text-3xl font-bold">{{ $coupon->code }}</h2>
                        <p class="text-green-100 mt-1">Discount Coupon</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">{{ $coupon->discount }}%</div>
                        <div class="text-green-100">OFF</div>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Status -->
                    <div class="text-center">
                        <div class="mb-2">
                            @if($coupon->expiry_date < now())
                                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-clock mr-1"></i>Expired
                                </span>
                            @elseif($coupon->usage_count >= $coupon->max_usage)
                                <span class="px-4 py-2 bg-orange-100 text-orange-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-ban mr-1"></i>Limit Reached
                                </span>
                            @else
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm">Current Status</p>
                    </div>

                    <!-- Usage Progress -->
                    <div class="text-center">
                        <div class="mb-2">
                            <div class="text-2xl font-bold text-gray-900">{{ $coupon->usage_count }}/{{ $coupon->max_usage }}</div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($coupon->usage_count / $coupon->max_usage) * 100 }}%"></div>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">Usage Progress</p>
                    </div>

                    <!-- Days Remaining -->
                    <div class="text-center">
                        <div class="mb-2">
                            @php
                                $daysRemaining = max(0, now()->diffInDays($coupon->expiry_date, false));
                            @endphp
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $daysRemaining >= 0 ? $daysRemaining : 0 }}
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">Days Remaining</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Coupon Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>Coupon Information
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Coupon Code:</span>
                        <span class="font-semibold">{{ $coupon->code }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Discount:</span>
                        <span class="font-semibold text-green-600">{{ $coupon->discount }}%</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Applicable Ticket:</span>
                        <span class="font-semibold">{{ $coupon->ticket->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Ticket Price:</span>
                        <span class="font-semibold">${{ $coupon->ticket->price ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Expiry Date:</span>
                        <span class="font-semibold">{{ date('M d, Y', strtotime($coupon->expiry_date)) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-semibold">{{ $coupon->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-chart-bar mr-2 text-purple-500"></i>Usage Statistics
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Times Used:</span>
                        <span class="font-semibold">{{ $coupon->usage_count }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Maximum Usage:</span>
                        <span class="font-semibold">{{ $coupon->max_usage }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Remaining Uses:</span>
                        <span class="font-semibold">{{ max(0, $coupon->max_usage - $coupon->usage_count) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Usage Rate:</span>
                        <span class="font-semibold">{{ round(($coupon->usage_count / $coupon->max_usage) * 100, 1) }}%</span>
                    </div>
                    @if($coupon->ticket)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">Potential Savings:</span>
                            <span class="font-semibold text-green-600">${{ round(($coupon->ticket->price * $coupon->discount / 100), 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-semibold">{{ $coupon->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        @if($coupon->ticket)
            <!-- Associated Ticket -->
            <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-ticket-alt mr-2 text-indigo-500"></i>Associated Ticket
                </h3>
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900">{{ $coupon->ticket->name }}</h4>
                            <p class="text-gray-600 mt-1">{{ $coupon->ticket->info ?? 'No additional information' }}</p>
                            <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500">
                                <span><i class="fas fa-dollar-sign mr-1"></i>Price: ${{ $coupon->ticket->price }}</span>
                                <span><i class="fas fa-boxes mr-1"></i>Quantity: {{ $coupon->ticket->quantity }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-lg font-bold text-green-600">
                                ${{ round($coupon->ticket->price - ($coupon->ticket->price * $coupon->discount / 100), 2) }}
                            </div>
                            <div class="text-sm text-gray-500">After {{ $coupon->discount }}% discount</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
