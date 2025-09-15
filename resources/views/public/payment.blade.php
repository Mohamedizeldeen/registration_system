<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - {{ $attendee->event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-credit-card text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Payment</h1>
                    <p class="text-sm text-gray-600">Complete your registration</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Payment Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Payment Information</h2>

                    <!-- Success Messages -->
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if($attendee->payment && $attendee->payment->status === 'completed')
                        <!-- Payment Already Completed -->
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check text-green-600 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">Payment Already Completed</h3>
                            <p class="text-gray-600 mb-6">Your registration and payment have been processed successfully.</p>
                            <a href="{{ route('public.confirmation', $attendee->id) }}" 
                               class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200">
                                View Confirmation
                            </a>
                        </div>
                    @else
                        <!-- Payment Form -->
                        <form method="POST" action="{{ route('public.payment.process', $attendee->id) }}" class="space-y-6">
                            @csrf

                            <!-- Mock Payment Notice -->
                            <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 mb-6">
                                <div class="flex items-start">
                                    <i class="fas fa-info-circle text-yellow-600 mt-1 mr-3"></i>
                                    <div>
                                        <h4 class="font-medium text-yellow-800">Demo Payment System</h4>
                                        <p class="text-sm text-yellow-700 mt-1">
                                            This is a demonstration payment system. In a real implementation, this would integrate with actual payment processors like Stripe, PayPal, or other payment gateways.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h3>
                                <div class="space-y-3">
                                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                        <input type="radio" name="payment_method" value="credit_card" checked class="text-blue-600">
                                        <div class="ml-3 flex items-center">
                                            <i class="fas fa-credit-card text-gray-400 mr-3"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">Credit/Debit Card</div>
                                                <div class="text-sm text-gray-600">Visa, Mastercard, American Express</div>
                                            </div>
                                        </div>
                                    </label>

                                    <label class="flex items-center p-4 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 opacity-50">
                                        <input type="radio" name="payment_method" value="paypal" disabled class="text-blue-600">
                                        <div class="ml-3 flex items-center">
                                            <i class="fab fa-paypal text-gray-400 mr-3"></i>
                                            <div>
                                                <div class="font-medium text-gray-900">PayPal</div>
                                                <div class="text-sm text-gray-600">Pay with your PayPal account (Coming Soon)</div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Mock Card Details -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Card Details (Demo)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Card Number</label>
                                        <input type="text" 
                                               value="4242 4242 4242 4242" 
                                               readonly
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                        <p class="text-xs text-gray-500 mt-1">Demo card number - no real payment will be processed</p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                        <input type="text" 
                                               value="12/25" 
                                               readonly
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">CVV</label>
                                        <input type="text" 
                                               value="123" 
                                               readonly
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Cardholder Name</label>
                                        <input type="text" 
                                               value="{{ $attendee->full_name }}" 
                                               readonly
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600">
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="pt-4">
                                <button type="submit" 
                                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                    <i class="fas fa-lock mr-2"></i>
                                    Complete Payment ({{ $attendee->ticket->currency->symbol ?? '$' }}{{ number_format($attendee->ticket->price, 2) }})
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>

                    <!-- Attendee Info -->
                    <div class="space-y-3 mb-6">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $attendee->full_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $attendee->email }}</p>
                        </div>
                    </div>

                    <!-- Event Details -->
                    <div class="space-y-3 mb-6 border-t border-gray-200 pt-4">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $attendee->event->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $attendee->event->company->name }}</p>
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                {{ $attendee->event->event_date->format('M j, Y') }}
                            </div>
                            @if($attendee->event->start_time)
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    {{ $attendee->event->start_time }}
                                    @if($attendee->event->end_time) - {{ $attendee->event->end_time }} @endif
                                </div>
                            @endif
                            @if($attendee->event->location)
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                    {{ $attendee->event->location }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="font-medium text-gray-900 mb-3">Ticket Details</h4>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h5 class="font-medium text-gray-900">{{ $attendee->ticket->name }}</h5>
                                    @if($attendee->ticket->info)
                                        <p class="text-xs text-gray-600 mt-1">{{ $attendee->ticket->info }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">
                                        {{ $attendee->ticket->currency->symbol ?? '$' }}{{ number_format($attendee->ticket->price, 2) }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Zone Access -->
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Access to {{ $attendee->ticket->eventZone->name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-green-600">
                                {{ $attendee->ticket->currency->symbol ?? '$' }}{{ number_format($attendee->ticket->price, 2) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $attendee->ticket->currency->code ?? 'USD' }}</p>
                    </div>

                    <!-- Payment Status -->
                    @if($attendee->payment)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600">Payment Status</span>
                                @if($attendee->payment->status === 'completed')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pending
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="text-center text-gray-600">
                <p>&copy; 2025 Event Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
