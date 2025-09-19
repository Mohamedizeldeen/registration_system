<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Currency</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-6">
            <div class="flex items-center">
                <a href="{{ route('currencies.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">
                    <i class="fas fa-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Currency</h1>
                    <p class="text-gray-600 mt-2">Configure a new currency for your event pricing</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('currencies.store') }}" method="POST">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Currency Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-code mr-2"></i>Currency Code
                        </label>
                        <input type="text" id="code" name="code" required maxlength="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                            placeholder="e.g., USD, EUR, GBP"
                            value="{{ old('code') }}">
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">3-letter ISO currency code</p>
                    </div>

                    <!-- Currency Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-font mr-2"></i>Currency Name
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., US Dollar, Euro, British Pound"
                            value="{{ old('name') }}">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Currency Symbol -->
                    <div>
                        <label for="symbol" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign mr-2"></i>Currency Symbol
                        </label>
                        <input type="text" id="symbol" name="symbol" required maxlength="5"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="e.g., $, €, £"
                            value="{{ old('symbol') }}">
                        @error('symbol')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Exchange Rate -->
                    <div>
                        <label for="exchange_rate" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-exchange-alt mr-2"></i>Exchange Rate (to USD)
                        </label>
                        <input type="number" id="exchange_rate" name="exchange_rate" required step="0.0001" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="1.0000"
                            value="{{ old('exchange_rate', '1.0000') }}">
                        @error('exchange_rate')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">1 USD = X units of this currency</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-toggle-on mr-2"></i>Status
                        </label>
                        <select id="is_active" name="is_active" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('is_active')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sort mr-2"></i>Display Position
                        </label>
                        <input type="number" id="position" name="position" min="0"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="0"
                            value="{{ old('position', '0') }}">
                        @error('position')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <!-- Popular Currencies Quick Select -->
                <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-4">Quick Select Popular Currencies</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                        <button type="button" class="currency-quick-select p-3 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200"
                                data-code="USD" data-name="US Dollar" data-symbol="$" data-rate="1.0000">
                            <div class="font-semibold">USD</div>
                            <div class="text-sm text-gray-600">US Dollar</div>
                            <div class="text-xs">$</div>
                        </button>
                        <button type="button" class="currency-quick-select p-3 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200"
                                data-code="EUR" data-name="Euro" data-symbol="€" data-rate="0.8500">
                            <div class="font-semibold">EUR</div>
                            <div class="text-sm text-gray-600">Euro</div>
                            <div class="text-xs">€</div>
                        </button>
                        <button type="button" class="currency-quick-select p-3 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200"
                                data-code="GBP" data-name="British Pound" data-symbol="£" data-rate="0.7500">
                            <div class="font-semibold">GBP</div>
                            <div class="text-sm text-gray-600">British Pound</div>
                            <div class="text-xs">£</div>
                        </button>
                        <button type="button" class="currency-quick-select p-3 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200"
                                data-code="JPY" data-name="Japanese Yen" data-symbol="¥" data-rate="110.0000">
                            <div class="font-semibold">JPY</div>
                            <div class="text-sm text-gray-600">Japanese Yen</div>
                            <div class="text-xs">¥</div>
                        </button>
                        <button type="button" class="currency-quick-select p-3 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200"
                                data-code="CAD" data-name="Canadian Dollar" data-symbol="C$" data-rate="1.2500">
                            <div class="font-semibold">CAD</div>
                            <div class="text-sm text-gray-600">Canadian Dollar</div>
                            <div class="text-xs">C$</div>
                        </button>
                        <button type="button" class="currency-quick-select p-3 border border-gray-300 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition duration-200"
                                data-code="AUD" data-name="Australian Dollar" data-symbol="A$" data-rate="1.3500">
                            <div class="font-semibold">AUD</div>
                            <div class="text-sm text-gray-600">Australian Dollar</div>
                            <div class="text-xs">A$</div>
                        </button>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('currencies.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>Add Currency
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Quick select functionality
        document.querySelectorAll('.currency-quick-select').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('code').value = this.dataset.code;
                document.getElementById('name').value = this.dataset.name;
                document.getElementById('symbol').value = this.dataset.symbol;
                document.getElementById('exchange_rate').value = this.dataset.rate;
                
                // Visual feedback
                document.querySelectorAll('.currency-quick-select').forEach(btn => {
                    btn.classList.remove('bg-blue-100', 'border-blue-400');
                });
                this.classList.add('bg-blue-100', 'border-blue-400');
            });
        });

        // Auto-uppercase currency code
        document.getElementById('code').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    </script>
</body>
</html>
