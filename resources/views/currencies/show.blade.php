<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Currency Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('currencies.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Currency Details</h1>
                        <p class="text-gray-600 mt-2">View currency information and usage statistics</p>
                    </div>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('currencies.edit', $currency->id) }}" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('currencies.destroy', $currency->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this currency?')">
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
        <!-- Currency Overview Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-8 py-6">
                <div class="flex justify-between items-center text-white">
                    <div>
                        <h2 class="text-3xl font-bold">{{ $currency->code ?? 'CUR' }}</h2>
                        <p class="text-blue-100 mt-1">{{ $currency->name ?? 'Currency Name' }}</p>
                    </div>
                    <div class="text-right">
                        <div class="text-4xl font-bold">{{ $currency->symbol ?? '$' }}</div>
                        <div class="text-blue-100">Symbol</div>
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Status -->
                    <div class="text-center">
                        <div class="mb-2">
                            @if($currency->is_active ?? true)
                                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-check-circle mr-1"></i>Active
                                </span>
                            @else
                                <span class="px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-times-circle mr-1"></i>Inactive
                                </span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm">Current Status</p>
                    </div>

                    <!-- Exchange Rate -->
                    <div class="text-center">
                        <div class="mb-2">
                            <div class="text-2xl font-bold text-gray-900">{{ $currency->exchange_rate ?? '1.0000' }}</div>
                        </div>
                        <p class="text-gray-600 text-sm">Exchange Rate (USD)</p>
                    </div>

                    <!-- Position -->
                    <div class="text-center">
                        <div class="mb-2">
                            <div class="text-2xl font-bold text-gray-900">{{ $currency->position ?? '0' }}</div>
                        </div>
                        <p class="text-gray-600 text-sm">Display Position</p>
                    </div>

                    <!-- Days Since Created -->
                    <div class="text-center">
                        <div class="mb-2">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $currency->created_at->diffInDays() ?? '0' }}
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm">Days Active</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Currency Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2 text-blue-500"></i>Currency Information
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Currency Code:</span>
                        <span class="font-semibold">{{ $currency->code ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Full Name:</span>
                        <span class="font-semibold">{{ $currency->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Symbol:</span>
                        <span class="font-semibold text-2xl">{{ $currency->symbol ?? '$' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Exchange Rate:</span>
                        <span class="font-semibold">{{ $currency->exchange_rate ?? '1.0000' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold {{ ($currency->is_active ?? true) ? 'text-green-600' : 'text-red-600' }}">
                            {{ ($currency->is_active ?? true) ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600">Display Position:</span>
                        <span class="font-semibold">{{ $currency->position ?? '0' }}</span>
                    </div>
                </div>
            </div>

            <!-- Exchange Rate Examples -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4">
                    <i class="fas fa-calculator mr-2 text-green-500"></i>Price Conversion Examples
                </h3>
                <div class="space-y-4">
                    @php
                        $rate = $currency->exchange_rate ?? 1;
                        $symbol = $currency->symbol ?? '$';
                        $examples = [10, 25, 50, 100, 250, 500];
                    @endphp
                    
                    @foreach($examples as $usdAmount)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100">
                            <span class="text-gray-600">${{ $usdAmount }} USD =</span>
                            <span class="font-semibold text-lg">{{ $symbol }}{{ number_format($usdAmount * $rate, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                
                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-info-circle mr-1"></i>
                        All conversions are based on the current exchange rate of {{ $rate }} {{ $currency->code ?? 'CUR' }} per 1 USD.
                    </p>
                </div>
            </div>
        </div>

        <!-- Usage Statistics (if tickets use this currency) -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-chart-bar mr-2 text-purple-500"></i>Usage Statistics
            </h3>
            
            @if(isset($currency->tickets) && $currency->tickets->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600">{{ $currency->tickets->count() }}</div>
                        <div class="text-sm text-gray-600">Tickets Using This Currency</div>
                    </div>
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600">
                            {{ $symbol }}{{ number_format($currency->tickets->sum('price'), 2) }}
                        </div>
                        <div class="text-sm text-gray-600">Total Value</div>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ $symbol }}{{ number_format($currency->tickets->avg('price'), 2) }}
                        </div>
                        <div class="text-sm text-gray-600">Average Price</div>
                    </div>
                </div>
                
                <!-- Recent Tickets -->
                <div class="mt-6">
                    <h4 class="text-lg font-semibold text-gray-700 mb-3">Recent Tickets Using This Currency</h4>
                    <div class="space-y-2">
                        @foreach($currency->tickets->take(5) as $ticket)
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <div>
                                    <div class="font-semibold">{{ $ticket->name }}</div>
                                    <div class="text-sm text-gray-600">{{ $ticket->event->name ?? 'Event' }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold">{{ $symbol }}{{ $ticket->price }}</div>
                                    <div class="text-sm text-gray-600">Qty: {{ $ticket->quantity }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-ticket-alt text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-600">No tickets are currently using this currency.</p>
                    <p class="text-sm text-gray-500 mt-1">Start creating tickets with this currency to see usage statistics.</p>
                </div>
            @endif
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-md p-6 mt-8">
            <h3 class="text-xl font-semibold text-gray-900 mb-4">
                <i class="fas fa-history mr-2 text-indigo-500"></i>Timeline
            </h3>
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                    <div>
                        <span class="font-semibold">Currency Created</span>
                        <span class="text-gray-600 ml-2">{{ $currency->created_at->format('M d, Y \a\t g:i A') ?? 'Recently' }}</span>
                    </div>
                </div>
                @if($currency->updated_at != $currency->created_at)
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <div>
                            <span class="font-semibold">Last Updated</span>
                            <span class="text-gray-600 ml-2">{{ $currency->updated_at->format('M d, Y \a\t g:i A') ?? 'N/A' }}</span>
                        </div>
                    </div>
                @endif
                <div class="flex items-center space-x-3">
                    <div class="w-3 h-3 bg-gray-400 rounded-full"></div>
                    <div>
                        <span class="font-semibold">Status</span>
                        <span class="text-gray-600 ml-2">{{ ($currency->is_active ?? true) ? 'Currently Active' : 'Currently Inactive' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
