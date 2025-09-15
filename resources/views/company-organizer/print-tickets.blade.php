@extends('layouts.app')

@section('title', 'Print Tickets')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Print Tickets</h1>
                <p class="text-gray-600">Generate A6 PDF tickets for your company's attendees</p>
            </div>
            
            <a href="{{ route('company-organizer.attendees') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Attendees
            </a>
        </div>

        @if(session('debug_info'))
        <!-- Debug Information -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <h3 class="text-sm font-medium text-yellow-800 mb-2">Debug Information:</h3>
            <div class="text-sm text-yellow-700">
                <p><strong>Search term:</strong> {{ session('debug_info.search_term') }}</p>
                <p><strong>Company ID:</strong> {{ session('debug_info.company_id') }}</p>
                <p><strong>Total attendees in company:</strong> {{ session('debug_info.total_company_attendees') }}</p>
                <p><strong>Search results found:</strong> {{ session('debug_info.search_results_count') }}</p>
            </div>
        </div>
        @endif

        <!-- Search and Print Form -->
        <div class="bg-gray-50 rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Find Attendees</h2>
            
            <!-- Search Options -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Search by Name/Email -->
                <div>
                    <h3 class="text-md font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-search mr-2 text-blue-600"></i>
                        Search by Name or Email
                    </h3>
                    <form method="GET" action="{{ route('company-organizer.print-tickets.form') }}" class="space-y-3">
                        <div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Enter name, email, or attendee ID..." 
                                   class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                            <i class="fas fa-search mr-2"></i>
                            Search Attendees
                        </button>
                    </form>
                </div>

                <!-- QR Code Scanner -->
                <div>
                    <h3 class="text-md font-medium text-gray-700 mb-3 flex items-center">
                        <i class="fas fa-qrcode mr-2 text-green-600"></i>
                        QR Code Scanner
                    </h3>
                    <div class="space-y-3">
                        <div class="bg-white border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                            <button type="button" onclick="startQRScanner()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center mx-auto">
                                <i class="fas fa-camera mr-2"></i>
                                Start QR Scanner
                            </button>
                            <p class="text-sm text-gray-500 mt-2">Scan attendee badge or QR code</p>
                        </div>
                        
                        <!-- Manual ID Input -->
                        <div>
                            <input type="number" id="manual-id" placeholder="Or enter Attendee ID manually..." 
                                   class="w-full border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500">
                            <button type="button" onclick="printByManualId()" class="mt-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center w-full justify-center">
                                <i class="fas fa-print mr-2"></i>
                                Quick Print by ID
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Scanner Modal -->
        <div id="qr-scanner-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div class="bg-white rounded-lg p-6 w-full max-w-md">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">QR Code Scanner</h3>
                        <button onclick="stopQRScanner()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div id="qr-reader" class="w-full"></div>
                    <div class="mt-4 text-sm text-gray-600">
                        <p>Position the QR code within the camera view to scan.</p>
                    </div>
                </div>
            </div>
        </div>

        @if(request('search') && $searchResults->count() > 0)
        <!-- Search Results -->
        <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">
                    Search Results ({{ $searchResults->count() }} found)
                </h2>
                <a href="{{ route('company-organizer.print-tickets.form') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Clear Search
                </a>
            </div>
            
            <!-- Search Results Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($searchResults as $attendee)
                <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                    <div class="space-y-3">
                        <!-- Attendee Info -->
                        <div>
                            <div class="font-medium text-gray-900">{{ $attendee->full_name }}</div>
                            <div class="text-sm text-gray-600">{{ $attendee->email }}</div>
                            <div class="text-xs text-gray-500">ID: {{ $attendee->id }}</div>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="text-sm">
                            <div class="font-medium text-gray-700">{{ $attendee->event->name }}</div>
                            <div class="text-gray-500">{{ $attendee->event->start_date?->format('M d, Y H:i') }}</div>
                        </div>
                        
                        <!-- Ticket Info -->
                        @if($attendee->ticket)
                        <div class="text-xs text-gray-600">
                            <div>{{ $attendee->ticket->name }}</div>
                            <div>{{ $attendee->ticket->eventZone->name ?? 'N/A' }}</div>
                            <div>{{ $attendee->ticket->currency->code ?? 'USD' }} {{ number_format($attendee->ticket->price, 2) }}</div>
                        </div>
                        @endif
                        
                        <!-- Status -->
                        <div>
                            @if($attendee->checked_in)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check mr-1"></i>Checked In
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @endif
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <a href="{{ route('company-organizer.quick-print', $attendee->id) }}" 
                               class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-center py-2 px-3 rounded text-sm font-medium"
                               target="_blank">
                                <i class="fas fa-print mr-1"></i>Print Ticket
                            </a>
                            <form method="POST" action="{{ route('company-organizer.print-tickets') }}" class="flex-1" target="_blank">
                                @csrf
                                <input type="hidden" name="attendee_ids[]" value="{{ $attendee->id }}">
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white text-center py-2 px-3 rounded text-sm font-medium">
                                    <i class="fas fa-download mr-1"></i>PDF
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @elseif(request('search'))
        <!-- No search results -->
        <div class="text-center py-12">
            <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Results Found</h3>
            <p class="text-gray-600 mb-4">No attendees found matching "{{ request('search') }}"</p>
            <a href="{{ route('company-organizer.print-tickets.form') }}" class="text-blue-600 hover:text-blue-800 font-medium">
                Clear Search
            </a>
        </div>

        @elseif(!request('search'))
        <!-- Event Selection (only show when not searching) -->
        <form method="GET" action="{{ route('company-organizer.print-tickets.form') }}" class="mb-6">
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Or Browse by Event</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                        <select name="event_id" id="event_id" class="w-full border-gray-300 rounded-lg" onchange="this.form.submit()">
                            <option value="">Select an event</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                    {{ $event->name }} - {{ $event->start_date?->format('M d, Y') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </form>
        @endif

        @if(request('event_id') && $attendees->count() > 0)
        <!-- Attendees Selection -->
        <form method="POST" action="{{ route('company-organizer.print-tickets') }}" target="_blank">
            @csrf
            <input type="hidden" name="event_id" value="{{ request('event_id') }}">
            
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Select Attendees</h2>
                    <div class="flex space-x-2">
                        <button type="button" onclick="selectAll()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Select All
                        </button>
                        <span class="text-gray-400">|</span>
                        <button type="button" onclick="selectNone()" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Select None
                        </button>
                    </div>
                </div>
                
                <!-- Summary -->
                <div class="mb-4 p-4 bg-blue-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                        <span class="text-blue-800 font-medium">
                            Found {{ $attendees->count() }} attendees for this event
                        </span>
                    </div>
                </div>

                <!-- Attendees Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto">
                    @foreach($attendees as $attendee)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" name="attendee_ids[]" value="{{ $attendee->id }}" 
                                   class="attendee-checkbox mt-1 h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <div class="ml-3 flex-1">
                                <div class="font-medium text-gray-900">{{ $attendee->full_name }}</div>
                                <div class="text-sm text-gray-600">{{ $attendee->email }}</div>
                                @if($attendee->ticket)
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $attendee->ticket->name }} - {{ $attendee->ticket->eventZone->name ?? 'N/A' }}
                                    </div>
                                @endif
                                <div class="mt-2">
                                    @if($attendee->checked_in)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i>Checked In
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Print Options -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Print Options</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Ticket Format</h3>
                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                <span class="text-sm text-gray-900">A6 PDF Format (105 × 148 mm)</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-print text-gray-500 mr-2"></i>
                                <span class="text-sm text-gray-600">Optimized for standard printers</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="text-sm font-medium text-gray-700 mb-2">Included Information</h3>
                        <div class="space-y-1 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                Event details and venue
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                Attendee information
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                Ticket type and zone
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2 text-xs"></i>
                                QR code for check-in
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Print Button -->
            <div class="flex justify-center">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg flex items-center text-lg font-medium">
                    <i class="fas fa-print mr-3"></i>
                    Generate PDF Tickets
                </button>
            </div>
        </form>

        @elseif(request('event_id'))
        <!-- No attendees found -->
        <div class="text-center py-12">
            <i class="fas fa-users text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Attendees Found</h3>
            <p class="text-gray-600">The selected event doesn't have any attendees yet.</p>
        </div>

        @elseif(!request('search') && !request('event_id'))
        <!-- No event selected and no search -->
        <div class="text-center py-12">
            <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Find Attendees to Print Tickets</h3>
            <p class="text-gray-600">Use the search above to find specific attendees, scan QR codes, or select an event to browse all attendees.</p>
        </div>
        @endif
    </div>
</div>

<!-- Include QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
let html5QrcodeScanner = null;

function selectAll() {
    const checkboxes = document.querySelectorAll('.attendee-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = true);
}

function selectNone() {
    const checkboxes = document.querySelectorAll('.attendee-checkbox');
    checkboxes.forEach(checkbox => checkbox.checked = false);
}

function startQRScanner() {
    document.getElementById('qr-scanner-modal').classList.remove('hidden');
    
    html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader",
        { 
            fps: 10, 
            qrbox: { width: 250, height: 250 },
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            }
        },
        false
    );
    
    html5QrcodeScanner.render(onScanSuccess, onScanFailure);
}

function stopQRScanner() {
    if (html5QrcodeScanner) {
        html5QrcodeScanner.clear();
        html5QrcodeScanner = null;
    }
    document.getElementById('qr-scanner-modal').classList.add('hidden');
}

function onScanSuccess(decodedText, decodedResult) {
    console.log(`QR Code scanned: ${decodedText}`);
    
    // Extract attendee ID from QR code
    // Assuming QR code contains just the attendee ID or a URL with the ID
    let attendeeId = decodedText;
    
    // If it's a URL, extract the ID
    if (decodedText.includes('/')) {
        const parts = decodedText.split('/');
        attendeeId = parts[parts.length - 1];
    }
    
    // Check if it's a valid number
    if (!isNaN(attendeeId) && attendeeId > 0) {
        stopQRScanner();
        
        // Show loading message
        alert('QR Code scanned! Generating ticket...');
        
        // Redirect to quick print
        window.open(`{{ url('company-organizer/quick-print') }}/${attendeeId}`, '_blank');
    } else {
        alert('Invalid QR code. Please scan an attendee QR code.');
    }
}

function onScanFailure(error) {
    // Handle scan failure - usually just ignore
    console.log(`QR Code scan error: ${error}`);
}

function printByManualId() {
    const attendeeId = document.getElementById('manual-id').value;
    
    if (!attendeeId || isNaN(attendeeId) || attendeeId <= 0) {
        alert('Please enter a valid attendee ID.');
        return;
    }
    
    // Redirect to quick print
    window.open(`{{ url('company-organizer/quick-print') }}/${attendeeId}`, '_blank');
}

// Auto-select all attendees when event is loaded
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelectorAll('.attendee-checkbox').length > 0) {
        selectAll();
    }
});

// Handle Enter key in manual ID input
document.getElementById('manual-id')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        printByManualId();
    }
});
</script>
@endsection
