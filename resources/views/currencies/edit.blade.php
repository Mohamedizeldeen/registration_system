<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Currency</title>
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
                    <h1 class="text-3xl font-bold text-gray-900">Edit Currency</h1>
                    <p class="text-gray-600 mt-2">Update currency information and settings</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-8">
            <form action="{{ route('currencies.update', $currency->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Currency Code -->
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-code mr-2"></i>Currency Code
                        </label>
                        <input type="text" id="code" name="code" required maxlength="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase"
                            value="{{ old('code', $currency->code ?? '') }}">
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
                            value="{{ old('name', $currency->name ?? '') }}">
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
                            value="{{ old('symbol', $currency->symbol ?? '') }}">
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
                            value="{{ old('exchange_rate', $currency->exchange_rate ?? '1.0000') }}">
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
                            <option value="1" {{ old('is_active', $currency->is_active ?? true) ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $currency->is_active ?? true) ? '' : 'selected' }}>Inactive</option>
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
                            value="{{ old('position', $currency->position ?? '0') }}">
                        @error('position')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Current Information</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Created:</span>
                            <div class="font-semibold">{{ $currency->created_at->format('M d, Y') ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Last Updated:</span>
                            <div class="font-semibold">{{ $currency->updated_at->diffForHumans() ?? 'N/A' }}</div>
                        </div>
                        <div>
                            <span class="text-gray-600">Status:</span>
                            <div class="font-semibold {{ ($currency->is_active ?? true) ? 'text-green-600' : 'text-red-600' }}">
                                {{ ($currency->is_active ?? true) ? 'Active' : 'Inactive' }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-600">Current Rate:</span>
                            <div class="font-semibold">{{ $currency->exchange_rate ?? '1.0000' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Exchange Rate Helper -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-blue-800 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Exchange Rate Information
                    </h3>
                    <p class="text-blue-700 text-sm mb-3">
                        The exchange rate determines how prices are converted. For example, if 1 USD = 0.85 EUR, 
                        then a $100 ticket would cost €85.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                        <div class="bg-white p-3 rounded">
                            <div class="text-gray-600">Current Rate:</div>
                            <div class="font-semibold">1 USD = {{ $currency->exchange_rate ?? '1.0000' }} {{ $currency->code ?? 'CUR' }}</div>
                        </div>
                        <div class="bg-white p-3 rounded">
                            <div class="text-gray-600">Example $100 USD:</div>
                            <div class="font-semibold">{{ $currency->symbol ?? '$' }}{{ number_format(100 * ($currency->exchange_rate ?? 1), 2) }}</div>
                        </div>
                        <div class="bg-white p-3 rounded">
                            <div class="text-gray-600">Example $50 USD:</div>
                            <div class="font-semibold">{{ $currency->symbol ?? '$' }}{{ number_format(50 * ($currency->exchange_rate ?? 1), 2) }}</div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-4 mt-8">
                    <a href="{{ route('currencies.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200 flex items-center">
                        <i class="fas fa-save mr-2"></i>Update Currency
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-uppercase currency code
        document.getElementById('code').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Update example calculations when exchange rate changes
        document.getElementById('exchange_rate').addEventListener('input', function() {
            const rate = parseFloat(this.value) || 1;
            const symbol = document.getElementById('symbol').value || '$';
            
            // Update examples if they exist
            const examples = document.querySelectorAll('.font-semibold');
            examples.forEach(example => {
                if (example.textContent.includes('$100')) {
                    example.textContent = symbol + (100 * rate).toFixed(2);
                } else if (example.textContent.includes('$50')) {
                    example.textContent = symbol + (50 * rate).toFixed(2);
                }
            });
        });
    </script>
</body>
</html>
