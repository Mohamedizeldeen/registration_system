@extends('layouts.app')

@section('content')
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Currency Management</h1>
                    <p class="text-gray-600 mt-2">Manage supported currencies for ticket pricing</p>
                </div>
                <a href="{{ route('currencies.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>Add Currency
                </a>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="max-w-6xl mx-auto px-4 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        @if(isset($currencies) && $currencies->count() > 0)
            <!-- Currencies Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($currencies as $currency)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-200">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-dollar-sign text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $currency->code ?? 'USD' }}</h3>
                                        <p class="text-sm text-gray-600">{{ $currency->name ?? 'US Dollar' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Symbol:</span>
                                    <span class="font-semibold">{{ $currency->symbol ?? '$' }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Exchange Rate:</span>
                                    <span class="font-semibold">{{ $currency->exchange_rate ?? '1.00' }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-semibold {{ ($currency->is_active ?? true) ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ($currency->is_active ?? true) ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    Created {{ $currency->created_at->diffForHumans() ?? 'Recently' }}
                                </div>
                                
                                <div class="flex space-x-2">
                                    <a href="{{ route('currencies.show', $currency->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('currencies.edit', $currency->id) }}" class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('currencies.destroy', $currency->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this currency?')">
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

            <!-- Popular Currencies Info -->
            <div class="mt-12 bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-globe mr-2 text-blue-500"></i>Popular Currencies
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="font-semibold text-gray-900">USD</div>
                        <div class="text-sm text-gray-600">US Dollar</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="font-semibold text-gray-900">EUR</div>
                        <div class="text-sm text-gray-600">Euro</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="font-semibold text-gray-900">GBP</div>
                        <div class="text-sm text-gray-600">British Pound</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="font-semibold text-gray-900">JPY</div>
                        <div class="text-sm text-gray-600">Japanese Yen</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="font-semibold text-gray-900">CAD</div>
                        <div class="text-sm text-gray-600">Canadian Dollar</div>
                    </div>
                    <div class="text-center p-3 bg-gray-50 rounded-lg">
                        <div class="font-semibold text-gray-900">AUD</div>
                        <div class="text-sm text-gray-600">Australian Dollar</div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-coins text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Currencies Found</h3>
                <p class="text-gray-500 mb-6">Start by adding currencies to support international pricing for your events.</p>
                <a href="{{ route('currencies.create') }}" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200">
                    Add Your First Currency
                </a>
            </div>
        @endif
    </div>
@endsection
