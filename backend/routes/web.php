<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth as AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyAdminController;
use App\Http\Controllers\CompanyOrganizerController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventZoneController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('landing');
});

// Frontend SuperAdmin Dashboard Route
Route::get('/frontend/superadmin-dashboard', function () {
    return response()->file(base_path('Frontend/SuperAdmin-dashboard/index.html'));
});

// Frontend SuperAdmin Dashboard CSS Route
Route::get('/frontend/superadmin-dashboard/css/dashboard.css', function () {
    return response()->file(base_path('Frontend/SuperAdmin-dashboard/css/dashboard.css'), [
        'Content-Type' => 'text/css'
    ]);
});

// Frontend SuperAdmin Dashboard JS Route
Route::get('/frontend/superadmin-dashboard/js/dashboard.js', function () {
    return response()->file(base_path('Frontend/SuperAdmin-dashboard/js/dashboard.js'), [
        'Content-Type' => 'application/javascript'
    ]);
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// QR Code Check-in Route (public access)
Route::get('/check-in/{attendee}', [AttendeeController::class, 'qrCheckIn'])->name('qr.checkin');

Route::middleware(['auth'])->group(function () {
    // Super Admin Dashboard
    Route::get('/admin/dashboard', function () {
        $totalEvents = \App\Models\Event::count();
        $totalAttendees = \App\Models\Attendee::count();
        $totalRevenue = \App\Models\Payment::where('status', 'completed')->sum('amount');
        $totalZones = \App\Models\EventZone::count();
        
        // Get recent events for dashboard
        $recentEvents = \App\Models\Event::latest()->take(5)->get();
        
        // Get top performing events with revenue data
        $topEvents = \App\Models\Event::withCount('attendees')
            ->with(['payments' => function($query) {
                $query->where('status', 'completed');
            }])
            ->get()
            ->map(function($event) {
                $event->total_revenue = $event->payments->sum('amount');
                return $event;
            })
            ->sortByDesc('total_revenue')
            ->take(5);
        
        // Get recent activity (payments, new events, etc.)
        $recentPayments = \App\Models\Payment::with(['attendee.event'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();
            
        $recentNewEvents = \App\Models\Event::latest()->take(3)->get();
        
        return view('admin.dashboard', compact(
            'totalEvents',
            'totalAttendees', 
            'totalRevenue',
            'totalZones',
            'recentEvents',
            'topEvents',
            'recentPayments',
            'recentNewEvents'
        ));
    })->name('dashboard')->middleware('role:super_admin');
    
    // Company Admin Dashboard
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/company-admin/dashboard', [CompanyAdminController::class, 'dashboard'])
            ->name('company-admin.dashboard');
        
        // Company Admin specific routes (filtered by company)
        Route::get('/company-admin/events', [CompanyAdminController::class, 'events'])
            ->name('company-admin.events');
        Route::get('/company-admin/attendees', [CompanyAdminController::class, 'attendees'])
            ->name('company-admin.attendees');
        Route::get('/company-admin/attendees/{attendee}', [CompanyAdminController::class, 'showAttendee'])
            ->name('company-admin.attendees.show');
        Route::get('/company-admin/payments', [CompanyAdminController::class, 'payments'])
            ->name('company-admin.payments');
        Route::get('/company-admin/tickets', [CompanyAdminController::class, 'tickets'])
            ->name('company-admin.tickets');
        Route::get('/company-admin/tickets/{ticket}', [CompanyAdminController::class, 'showTicket'])
            ->name('company-admin.tickets.show');
        
        // Print tickets routes
        Route::get('/company-admin/print-tickets', [CompanyAdminController::class, 'showPrintForm'])
            ->name('company-admin.print-tickets.form');
        Route::post('/company-admin/print-tickets', [CompanyAdminController::class, 'printTickets'])
            ->name('company-admin.print-tickets');
        Route::get('/company-admin/quick-print/{attendee}', [CompanyAdminController::class, 'quickPrintTicket'])
            ->name('company-admin.quick-print');
        
        // Test route for PDF generation
        Route::get('/company-admin/test-pdf', function() {
            $user = Auth::user();
            $attendee = \App\Models\Attendee::whereHas('event', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->with(['event.company', 'ticket.eventZone', 'ticket.currency'])->first();
            
            if (!$attendee) {
                return 'No attendees found for your company';
            }
            
            $attendees = collect([$attendee]);
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('tickets.print-a6', compact('attendees'))
                ->setPaper([0, 0, 297.64, 419.53], 'portrait');
            
            return $pdf->download('test-ticket.pdf');
        })->name('company-admin.test-pdf');
        
        // Test QR Code generation
        Route::get('/company-admin/test-qr', function() {
            $user = Auth::user();
            $attendee = \App\Models\Attendee::whereHas('event', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->with(['event.company', 'ticket.eventZone', 'ticket.currency'])->first();
            
            if (!$attendee) {
                return 'No attendees found for your company';
            }
            
            return '<div style="text-align: center; padding: 20px;">
                        <h2>QR Code Test for Attendee: ' . $attendee->full_name . '</h2>
                        <div style="margin: 20px;">
                            ' . \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($attendee->simple_qr_data) . '
                        </div>
                        <p>QR Data: ' . $attendee->simple_qr_data . '</p>
                        <p><a href="' . $attendee->simple_qr_data . '" target="_blank">Test QR Link</a></p>
                    </div>';
        })->name('company-admin.test-qr');
    });
    
    // Company Organizer Dashboard
    Route::middleware(['role:organizer'])->group(function () {
        Route::get('/company-organizer/dashboard', [CompanyOrganizerController::class, 'dashboard'])
            ->name('company-organizer.dashboard');
        
        // Company Organizer specific routes (read-only, filtered by company)
        Route::get('/company-organizer/events', [CompanyOrganizerController::class, 'events'])
            ->name('company-organizer.events');
        Route::get('/company-organizer/attendees', [CompanyOrganizerController::class, 'attendees'])
            ->name('company-organizer.attendees');
        Route::get('/company-organizer/attendees/{attendee}', [CompanyOrganizerController::class, 'showAttendee'])
            ->name('company-organizer.attendees.show');
        Route::get('/company-organizer/payments', [CompanyOrganizerController::class, 'payments'])
            ->name('company-organizer.payments');
        Route::get('/company-organizer/tickets', [CompanyOrganizerController::class, 'tickets'])
            ->name('company-organizer.tickets');
        Route::get('/company-organizer/tickets/{ticket}', [CompanyOrganizerController::class, 'showTicket'])
            ->name('company-organizer.tickets.show');
            
        // Print ticket functionality for organizers
        Route::get('/company-organizer/print-tickets', [CompanyOrganizerController::class, 'showPrintForm'])
            ->name('company-organizer.print-tickets.form');
        Route::get('/print-tickets/form', [CompanyOrganizerController::class, 'printTicketsForm'])->name('print-tickets.form');
        Route::post('/print-tickets', [CompanyOrganizerController::class, 'printTickets'])->name('print-tickets');
        Route::get('/test-qr', [CompanyOrganizerController::class, 'testQr'])->name('test-qr');
        Route::get('/company-organizer/quick-print/{attendee}', [CompanyOrganizerController::class, 'quickPrintTicket'])
            ->name('company-organizer.quick-print');
        
        // Test QR Code generation for organizers
        Route::get('/company-organizer/test-qr', function() {
            $user = Auth::user();
            $attendee = \App\Models\Attendee::whereHas('event', function($q) use ($user) {
                $q->where('company_id', $user->company_id);
            })->with(['event.company', 'ticket.eventZone', 'ticket.currency'])->first();
            
            if (!$attendee) {
                return 'No attendees found for your company';
            }
            
            return '<div style="text-align: center; padding: 20px;">
                        <h2>QR Code Test for Attendee: ' . $attendee->full_name . '</h2>
                        <div style="margin: 20px;">
                            ' . \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($attendee->simple_qr_data) . '
                        </div>
                        <p>QR Data: ' . $attendee->simple_qr_data . '</p>
                        <p><a href="' . $attendee->simple_qr_data . '" target="_blank">Test QR Link</a></p>
                    </div>';
        })->name('company-organizer.test-qr');
    });

    Route::get('/superAdmin/CreateUsers', [AuthController::class, 'createUser'])->name('admin.CreateUsers.index');
    Route::post('/superAdmin/CreateUsers', [AuthController::class, 'register'])->name('admin.CreateUsers.post');
    
    // Super Admin only routes
    Route::middleware(['role:super_admin'])->group(function () {
        // Main resource routes - only super admin can access these
        Route::resource('companies', CompanyController::class);
        Route::resource('currencies', CurrencyController::class);
        Route::resource('coupons', CouponController::class);
        Route::resource('attendees', AttendeeController::class);
        Route::resource('payments', PaymentController::class);
        
        // Additional API routes - also super admin only
        Route::post('/coupons/validate', [CouponController::class, 'validateCoupon'])->name('coupons.validate');
        Route::post('/tickets/calculate-price', [TicketController::class, 'calculatePrice'])->name('tickets.calculate-price');
        Route::get('/attendees/event-tickets/{eventId}', [AttendeeController::class, 'getEventTickets'])->name('attendees.event-tickets');
        Route::patch('/attendees/{attendee}/checkin', [AttendeeController::class, 'checkin'])->name('attendees.checkin');
        Route::get('/attendees/export', [AttendeeController::class, 'export'])->name('attendees.export');
        Route::post('/payments/{id}/process', [PaymentController::class, 'processPayment'])->name('payments.process');
        Route::post('/payments/{id}/mark-failed', [PaymentController::class, 'markAsFailed'])->name('payments.mark-failed');
        Route::get('/payments/stats', [PaymentController::class, 'getStats'])->name('payments.stats');
    });
    
    // Tickets routes - accessible by super admin and company admins (with access control in controller)
    Route::middleware(['role:super_admin,admin'])->group(function () {
        Route::resource('tickets', TicketController::class);
        Route::get('/tickets/event-zones/{eventId}', [TicketController::class, 'getEventZones'])->name('tickets.event-zones');
    });
    
    // Events routes - accessible by super admin, company admin, and organizers (with access control in controller)
    Route::middleware(['role:super_admin,admin,organizer'])->group(function () {
        Route::resource('events', EventController::class);
    });
    
    // Event Zones routes - accessible by super admin, company admin, and organizers (with access control in controller)
    Route::middleware(['role:super_admin,admin,organizer'])->group(function () {
        Route::resource('eventZones', EventZoneController::class);
    });
});

// QR Code Check-in routes (publicly accessible for QR scanning)
Route::get('/check-in/{attendee}', function($attendeeId) {
    $attendee = App\Models\Attendee::findOrFail($attendeeId);
    
    // Verify the hash if provided
    if (request('hash')) {
        $expectedHash = substr(hash('sha256', $attendee->id . $attendee->email . config('app.key')), 0, 8);
        if (request('hash') !== $expectedHash) {
            abort(403, 'Invalid verification hash');
        }
    }
    
    return view('attendee.qr-checkin', compact('attendee'));
})->name('qr.checkin');

Route::post('/check-in/{attendee}/process', function($attendeeId) {
    $attendee = App\Models\Attendee::findOrFail($attendeeId);
    
    // Verify the hash if provided
    if (request('hash')) {
        $expectedHash = substr(hash('sha256', $attendee->id . $attendee->email . config('app.key')), 0, 8);
        if (request('hash') !== $expectedHash) {
            abort(403, 'Invalid verification hash');
        }
    }
    
    if (!$attendee->checked_in) {
        $attendee->checkIn();
        $message = 'Successfully checked in!';
        $status = 'success';
    } else {
        $message = 'Already checked in at ' . $attendee->checked_in_at->format('M j, Y g:i A');
        $status = 'info';
    }
    
    return response()->json([
        'status' => $status,
        'message' => $message,
        'checked_in_at' => $attendee->checked_in_at ? $attendee->checked_in_at->format('M j, Y g:i A') : null
    ]);
})->name('qr.checkin.process');

// Public Event Registration Routes (no authentication required)
Route::prefix('register')->name('public.')->group(function () {
    Route::get('/events', [App\Http\Controllers\PublicRegistrationController::class, 'showEvents'])->name('events');
    Route::get('/events/{event}/tickets', [App\Http\Controllers\PublicRegistrationController::class, 'showTickets'])->name('tickets');
    Route::get('/tickets/{ticket}/register', [App\Http\Controllers\PublicRegistrationController::class, 'showRegistrationForm'])->name('form');
    Route::post('/tickets/{ticket}/register', [App\Http\Controllers\PublicRegistrationController::class, 'processRegistration'])->name('process');
    Route::get('/payment/{attendee}', [App\Http\Controllers\PublicRegistrationController::class, 'showPayment'])->name('payment');
    Route::post('/payment/{attendee}', [App\Http\Controllers\PublicRegistrationController::class, 'processPayment'])->name('payment.process');
    Route::get('/confirmation/{attendee}', [App\Http\Controllers\PublicRegistrationController::class, 'showConfirmation'])->name('confirmation');
});

