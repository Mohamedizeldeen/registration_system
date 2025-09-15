<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for {{ $ticket->event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Event Registration</h1>
                        <p class="text-sm text-gray-600">Complete your registration</p>
                    </div>
                </div>
                <a href="{{ route('public.tickets', $ticket->event->id) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Tickets
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Registration Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Registration Information</h2>

                    <!-- Error Messages -->
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-6">
                            <h3 class="font-medium mb-2">Please correct the following errors:</h3>
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Success Messages -->
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-300 text-green-700 px-4 py-3 rounded-lg mb-6">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Registration Form -->
                    <form method="POST" action="{{ route('public.process', $ticket->id) }}" class="space-y-6">
                        @csrf

                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="first_name" 
                                           name="first_name" 
                                           value="{{ old('first_name') }}"
                                           required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           id="last_name" 
                                           name="last_name" 
                                           value="{{ old('last_name') }}"
                                           required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}"
                                           required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                                        Phone Number
                                    </label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Professional Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Professional Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">
                                        Company/Organization
                                    </label>
                                    <input type="text" 
                                           id="company" 
                                           name="company" 
                                           value="{{ old('company') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="job_title" class="block text-sm font-medium text-gray-700 mb-1">
                                        Job Title
                                    </label>
                                    <input type="text" 
                                           id="job_title" 
                                           name="job_title" 
                                           value="{{ old('job_title') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">
                                        Country
                                    </label>
                                    <input type="text" 
                                           id="country" 
                                           name="country" 
                                           value="{{ old('country') }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit" 
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                                <i class="fas fa-user-plus mr-2"></i>
                                Complete Registration
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>

                    <!-- Event Details -->
                    <div class="space-y-3 mb-6">
                        <div>
                            <h4 class="font-medium text-gray-900">{{ $ticket->event->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $ticket->event->company->name }}</p>
                        </div>
                        
                        <div class="text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                {{ $ticket->event->event_date->format('M j, Y') }}
                            </div>
                            @if($ticket->event->start_time)
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    {{ $ticket->event->start_time }}
                                    @if($ticket->event->end_time) - {{ $ticket->event->end_time }} @endif
                                </div>
                            @endif
                            @if($ticket->event->location)
                                <div class="flex items-center mt-1">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                    {{ $ticket->event->location }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="border-t border-gray-200 pt-4">
                        <h4 class="font-medium text-gray-900 mb-3">Selected Ticket</h4>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h5 class="font-medium text-gray-900">{{ $ticket->name }}</h5>
                                    @if($ticket->info)
                                        <p class="text-xs text-gray-600 mt-1">{{ $ticket->info }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">
                                        {{ $ticket->currency->symbol ?? '$' }}{{ number_format($ticket->price, 2) }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Zone Access -->
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Access to {{ $ticket->eventZone->name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-xl font-bold text-blue-600">
                                {{ $ticket->currency->symbol ?? '$' }}{{ number_format($ticket->price, 2) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ $ticket->currency->code ?? 'USD' }}</p>
                    </div>

                    <!-- Security Note -->
                    <div class="mt-6 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-xs text-blue-800">
                            <i class="fas fa-shield-alt mr-1"></i>
                            Your registration is secure and your data is protected.
                        </p>
                    </div>
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
