<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed - {{ $attendee->event->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-green-600 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check text-white"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900">Registration Confirmed</h1>
                    <p class="text-sm text-gray-600">Your ticket is ready!</p>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-3xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Registration Successful!</h2>
            <p class="text-lg text-gray-600">Thank you for registering. Your payment has been processed and your ticket is confirmed.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Event Details -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                    Event Details
                </h3>

                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900 text-lg">{{ $attendee->event->name }}</h4>
                        <p class="text-gray-600">{{ $attendee->event->company->name }}</p>
                    </div>

                    @if($attendee->event->description)
                        <div>
                            <h5 class="font-medium text-gray-900 mb-1">Description</h5>
                            <p class="text-gray-600 text-sm">{{ $attendee->event->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <h5 class="font-medium text-gray-900 mb-1">Date</h5>
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                {{ $attendee->event->event_date->format('l, F j, Y') }}
                            </p>
                        </div>

                        @if($attendee->event->start_time)
                            <div>
                                <h5 class="font-medium text-gray-900 mb-1">Time</h5>
                                <p class="text-gray-600 flex items-center">
                                    <i class="fas fa-clock mr-2 text-blue-500"></i>
                                    {{ $attendee->event->start_time }}
                                    @if($attendee->event->end_time) - {{ $attendee->event->end_time }} @endif
                                </p>
                            </div>
                        @endif

                        @if($attendee->event->location)
                            <div class="md:col-span-2">
                                <h5 class="font-medium text-gray-900 mb-1">Location</h5>
                                <p class="text-gray-600 flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                    {{ $attendee->event->location }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Attendee & Ticket Info -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-ticket-alt mr-2 text-green-600"></i>
                    Your Ticket
                </h3>

                <!-- Attendee Info -->
                <div class="space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Attendee Information</h4>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="font-medium text-gray-900">{{ $attendee->full_name }}</p>
                            <p class="text-gray-600 text-sm">{{ $attendee->email }}</p>
                            @if($attendee->phone)
                                <p class="text-gray-600 text-sm">{{ $attendee->phone }}</p>
                            @endif
                            @if($attendee->company)
                                <p class="text-gray-600 text-sm">{{ $attendee->company }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Ticket Type -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Ticket Type</h4>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $attendee->ticket->name }}</p>
                                    @if($attendee->ticket->info)
                                        <p class="text-gray-600 text-sm">{{ $attendee->ticket->info }}</p>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">
                                        {{ $attendee->ticket->currency->symbol ?? '$' }}{{ number_format($attendee->ticket->price, 2) }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $attendee->ticket->currency->code ?? 'USD' }}</p>
                                </div>
                            </div>
                            
                            <!-- Zone Access -->
                            <div>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>
                                    Access to {{ $attendee->ticket->eventZone->name }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Registration ID -->
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Registration Details</h4>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 text-sm">Registration ID</span>
                                <span class="font-mono text-sm">#{{ $attendee->id }}</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600 text-sm">Registration Date</span>
                                <span class="text-sm">{{ $attendee->created_at->format('M j, Y g:i A') }}</span>
                            </div>
                            @if($attendee->payment)
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 text-sm">Payment Status</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>
                                        Completed
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code Section -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-4 text-center flex items-center justify-center">
                <i class="fas fa-qrcode mr-2 text-purple-600"></i>
                Your Digital Ticket
            </h3>

            <div class="text-center">
                <div class="inline-block bg-white p-6 rounded-lg border-2 border-gray-200">
                    @if($attendee->qr_code)
                        {!! $attendee->qr_code !!}
                    @else
                        <div class="w-48 h-48 bg-gray-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-qrcode text-gray-400 text-4xl"></i>
                        </div>
                    @endif
                </div>

                <div class="mt-4 max-w-md mx-auto">
                    <h4 class="font-medium text-gray-900 mb-2">How to use your digital ticket:</h4>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><i class="fas fa-mobile-alt mr-2 text-blue-500"></i>Save this page or take a screenshot</p>
                        <p><i class="fas fa-qrcode mr-2 text-blue-500"></i>Show the QR code at the event entrance</p>
                        <p><i class="fas fa-check mr-2 text-blue-500"></i>Enjoy the event!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                What's Next?
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="flex items-start">
                    <i class="fas fa-envelope text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-blue-900">Check Your Email</h4>
                        <p class="text-blue-800">You will receive a confirmation email with your ticket details shortly.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-calendar-plus text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-blue-900">Add to Calendar</h4>
                        <p class="text-blue-800">Don't forget to add this event to your calendar so you don't miss it.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-mobile-alt text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-blue-900">Save Your Ticket</h4>
                        <p class="text-blue-800">Screenshot or bookmark this page to access your QR code at the event.</p>
                    </div>
                </div>
                <div class="flex items-start">
                    <i class="fas fa-question-circle text-blue-600 mt-1 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-blue-900">Need Help?</h4>
                        <p class="text-blue-800">Contact the event organizer if you have any questions.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <button onclick="window.print()" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                <i class="fas fa-print mr-2"></i>
                Print Ticket
            </button>
            <a href="{{ route('public.events') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                <i class="fas fa-calendar mr-2"></i>
                Browse More Events
            </a>
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

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-area, .print-area * {
                visibility: visible;
            }
            .print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>

    <script>
        // Add print-area class to main content for printing
        document.querySelector('main').classList.add('print-area');
    </script>
</body>
</html>
