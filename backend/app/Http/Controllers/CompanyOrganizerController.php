<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\Payment;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CompanyOrganizerController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        // Get basic statistics for this company (read-only)
        $totalEvents = Event::where('company_id', $companyId)->count();
        $totalAttendees = Attendee::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->count();
        $checkedInAttendees = Attendee::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('checked_in', true)->count();
        $totalRevenue = Payment::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('status', 'completed')->sum('amount');
        
        // Get events for this company
        $companyEvents = Event::where('company_id', $companyId)->latest()->take(5)->get();
        
        // Get recent check-ins for this company
        $recentCheckIns = Attendee::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('checked_in', true)
            ->with('event')
            ->latest('updated_at')
            ->take(5)
            ->get();
        
        // Get upcoming events
        $upcomingEvents = Event::where('company_id', $companyId)
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->take(3)
            ->get();
        
        return view('company-organizer.dashboard', compact(
            'totalEvents',
            'totalAttendees',
            'checkedInAttendees',
            'totalRevenue',
            'companyEvents',
            'recentCheckIns',
            'upcomingEvents'
        ));
    }
    
    public function attendees(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        $query = Attendee::with(['event', 'ticket'])
            ->whereHas('event', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            });
        
        // Filter by event if specified
        if ($request->has('event_id') && $request->event_id != '') {
            $query->where('event_id', $request->event_id);
        }
        
        $attendees = $query->paginate(20);
        $events = Event::where('company_id', $companyId)->get();
        
        return view('company-organizer.attendees', compact('attendees', 'events'));
    }
    
    /**
     * Print tickets for selected attendees (POST method)
     */
    public function printTickets(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        Log::info('CompanyOrganizer Print Tickets Request', [
            'user_id' => $user->id,
            'company_id' => $companyId,
            'request_data' => $request->all()
        ]);

        try {
            // Validate that attendee IDs are provided
            if (!$request->has('attendee_ids') || empty($request->attendee_ids)) {
                return redirect()->back()->with('error', 'Please select at least one attendee to print tickets for.');
            }

            // Get selected attendees (filtered by company)
            $attendees = Attendee::whereIn('id', $request->attendee_ids)
                ->whereHas('event', function($q) use ($companyId) {
                    $q->where('company_id', $companyId);
                })
                ->with(['event', 'ticket.eventZone', 'ticket.currency'])
                ->get();

            if ($attendees->isEmpty()) {
                return redirect()->back()->with('error', 'No valid attendees found for your company.');
            }

            Log::info('Found attendees for printing', [
                'count' => $attendees->count(),
                'attendee_ids' => $attendees->pluck('id')->toArray()
            ]);

            // Generate PDF with A6 size (105mm x 148mm)
            $pdf = Pdf::loadView('tickets.print-a6', compact('attendees'))
                ->setPaper([0, 0, 297.64, 419.53], 'portrait'); // A6 size in points

            $filename = 'tickets-' . date('Y-m-d-H-i-s') . '.pdf';
            
            Log::info('PDF generated successfully', ['filename' => $filename]);

            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Show print tickets form with search and QR functionality
     */
    public function showPrintForm(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        // Get company events
        $events = Event::where('company_id', $companyId)->get();
        
        // Search attendees
        $attendees = collect();
        $searchResults = collect();
        
        if ($request->filled('search')) {
            $search = $request->search;
            
            // First, let's check if we have any attendees for this company at all
            $totalCompanyAttendees = Attendee::whereHas('event', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->count();
            
            // Search by name, email, or attendee ID
            $searchResults = Attendee::whereHas('event', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('id', $search) // For QR code scanning (attendee ID)
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']); // Search full name
            })
            ->with(['event', 'ticket.eventZone', 'ticket.currency'])
            ->limit(20)
            ->get();
            
            // Add debug info to session for testing
            session()->flash('debug_info', [
                'search_term' => $search,
                'company_id' => $companyId,
                'total_company_attendees' => $totalCompanyAttendees,
                'search_results_count' => $searchResults->count()
            ]);
        }
        
        // Get attendees for selected event (if no search)
        if (!$request->filled('search') && $request->filled('event_id')) {
            $attendees = Attendee::where('event_id', $request->event_id)
                ->with(['ticket.eventZone'])
                ->get();
        }

        return view('company-organizer.print-tickets', compact('events', 'attendees', 'searchResults'));
    }

    /**
     * Quick print single ticket by attendee ID (for QR scanning)
     */
    public function quickPrintTicket($attendeeId)
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        $attendee = Attendee::whereHas('event', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })
        ->with(['event', 'ticket.eventZone', 'ticket.currency'])
        ->findOrFail($attendeeId);

        // Generate PDF for single ticket
        $attendees = collect([$attendee]);
        
        $pdf = Pdf::loadView('tickets.print-a6', compact('attendees'))
            ->setPaper([0, 0, 297.64, 419.53], 'portrait'); // A6 size

        return $pdf->download('ticket-' . $attendee->full_name . '-' . date('Y-m-d-H-i-s') . '.pdf');
    }

    public function events()
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        $events = Event::where('company_id', $companyId)
            ->with(['eventZones', 'tickets', 'attendees'])
            ->paginate(10);
        
        return view('events.index', compact('events'));
    }

    public function payments()
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        $payments = Payment::whereHas('attendee.event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->with(['attendee.event'])->paginate(10);
        
        return view('payments.index', compact('payments'));
    }

    public function tickets()
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        $tickets = Ticket::whereHas('eventZone.event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->with(['eventZone.event', 'currency'])->paginate(10);
        
        return view('tickets.index', compact('tickets'));
    }

    public function testQr()
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        $attendee = Attendee::whereHas('event', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->with(['event', 'ticket', 'ticket.eventZone'])->first();

        if (!$attendee) {
            return back()->with('error', 'No attendees found for testing.');
        }

        return view('qr.test', compact('attendee'));
    }

    public function showTicket($id)
    {
        $user = Auth::user();
        $companyId = $user->company_id;

        // Find ticket that belongs to the user's company
        $ticket = Ticket::with(['event', 'eventZone', 'currency', 'coupon', 'attendees'])
            ->whereHas('eventZone.event', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->findOrFail($id);

        return view('ticket.show', compact('ticket'));
    }

    public function showAttendee($id)
    {
        $user = Auth::user();
        $companyId = $user->company_id;

        // Find attendee that belongs to the user's company
        $attendee = Attendee::with(['event', 'ticket.eventZone', 'payments'])
            ->whereHas('event', function($query) use ($companyId) {
                $query->where('company_id', $companyId);
            })
            ->findOrFail($id);

        return view('company-organizer.attendee-show', compact('attendee'));
    }
}
