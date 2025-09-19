<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Attendee;
use App\Models\Payment;
use App\Models\EventZone;
use App\Models\Ticket;
use App\Models\Company;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CompanyAdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        // Get statistics for this company only
        $totalEvents = Event::where('company_id', $companyId)->count();
        $totalAttendees = Attendee::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->count();
        $totalRevenue = Payment::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('status', 'completed')->sum('amount');
        $checkedInAttendees = Attendee::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->where('checked_in', true)->count();
        
        // Get total zones for this company
        $totalZones = EventZone::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->count();
        
        // Get recent events for this company
        $recentEvents = Event::where('company_id', $companyId)->latest()->take(5)->get();
        
        // Get top performing events for this company
        $topEvents = Event::where('company_id', $companyId)
            ->withCount('attendees')
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
        
        // Get recent payments for this company
        $recentPayments = Payment::whereHas('event', function($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->with(['attendee.event'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();
        
        return view('company-admin.dashboard', compact(
            'totalEvents',
            'totalAttendees', 
            'totalRevenue',
            'totalZones',
            'checkedInAttendees',
            'recentEvents',
            'topEvents',
            'recentPayments'
        ));
    }
    
    // Override resource methods to filter by company
    public function events()
    {
        $user = Auth::user();
        $events = Event::where('company_id', $user->company_id)
            ->withCount('attendees')
            ->paginate(10);
        return view('company-admin.events', compact('events'));
    }
    
    public function attendees(Request $request)
    {
        $user = Auth::user();
        
        // Handle CSV export
        if ($request->has('export') && $request->export === 'csv') {
            return $this->exportAttendeesCSV($request);
        }
        
        $query = Attendee::whereHas('event', function($q) use ($user) {
            $q->where('company_id', $user->company_id);
        })->with(['event', 'ticket.currency', 'payment']);

        // Apply filters
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'checked_in') {
                $query->where('checked_in', true);
            } elseif ($request->status === 'not_checked_in') {
                $query->where('checked_in', false);
            }
        }
        
        $attendees = $query->paginate(20);
        
        return view('company-admin.attendees', compact('attendees'));
    }
    
    private function exportAttendeesCSV(Request $request)
    {
        $user = Auth::user();
        
        $query = Attendee::whereHas('event', function($q) use ($user) {
            $q->where('company_id', $user->company_id);
        })->with(['event', 'ticket.currency', 'payment']);

        // Apply the same filters as the regular attendees view
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'checked_in') {
                $query->where('checked_in', true);
            } elseif ($request->status === 'not_checked_in') {
                $query->where('checked_in', false);
            }
        }

        $attendees = $query->get();

        $filename = 'company_attendees_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($attendees) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Event Name',
                'Event Date',
                'Ticket Type',
                'Ticket Price',
                'Currency',
                'Check-in Status',
                'Check-in Time',
                'Payment Status',
                'Payment Amount',
                'Payment Date',
                'Created At'
            ]);

            // CSV data
            foreach ($attendees as $attendee) {
                fputcsv($file, [
                    $attendee->id,
                    $attendee->first_name,
                    $attendee->last_name,
                    $attendee->email,
                    $attendee->phone ?? '',
                    $attendee->event->name ?? '',
                    $attendee->event->event_date ? \Carbon\Carbon::parse($attendee->event->event_date)->format('Y-m-d') . ' ' . $attendee->event->start_time : '',
                    $attendee->ticket->name ?? '',
                    $attendee->ticket->price ?? '',
                    $attendee->ticket->currency->code ?? '',
                    $attendee->checked_in ? 'Checked In' : 'Not Checked In',
                    $attendee->checked_in_at ? $attendee->checked_in_at->format('Y-m-d H:i:s') : '',
                    $attendee->payment ? 'Paid' : 'Unpaid',
                    $attendee->payment->amount ?? '',
                    $attendee->payment && $attendee->payment->created_at ? $attendee->payment->created_at->format('Y-m-d H:i:s') : '',
                    $attendee->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function payments()
    {
        $user = Auth::user();
        $payments = Payment::whereHas('attendee.event', function($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->with(['attendee', 'attendee.event', 'ticket'])->paginate(20);
        
        return view('payments.index', compact('payments'));
    }
    
    public function tickets()
    {
        $user = Auth::user();
        $tickets = Ticket::whereHas('eventZone.event', function($query) use ($user) {
            $query->where('company_id', $user->company_id);
        })->with(['eventZone.event', 'currency'])->paginate(20);
        
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Print tickets for attendees (PDF A6 format)
     */
    public function printTickets(Request $request)
    {
        $user = Auth::user();
        $companyId = $user->company_id;
        
        // Debug: Log all request data
        Log::info('Print tickets request received:', [
            'all_data' => $request->all(),
            'has_attendee_ids' => $request->has('attendee_ids'),
            'attendee_ids_value' => $request->get('attendee_ids'),
            'filled_attendee_ids' => $request->filled('attendee_ids'),
            'company_id' => $companyId
        ]);
        
        $query = Attendee::whereHas('event', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->with(['event.company', 'ticket.eventZone', 'ticket.currency']);

        // Filter by event if specified
        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
            Log::info('Filtering by event_id: ' . $request->event_id);
        }

        // Filter by specific attendee IDs if provided
        if ($request->filled('attendee_ids')) {
            $attendeeIds = is_array($request->attendee_ids) ? $request->attendee_ids : explode(',', $request->attendee_ids);
            $query->whereIn('id', $attendeeIds);
            Log::info('Filtering by attendee IDs: ' . implode(',', $attendeeIds));
        }

        $attendees = $query->get();
        
        Log::info('Query executed, found attendees: ' . $attendees->count());

        // Debug: Force include at least one attendee for testing
        if ($attendees->isEmpty()) {
            // Get the first attendee from this company for testing
            $testAttendee = Attendee::whereHas('event', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })->with(['event.company', 'ticket.eventZone', 'ticket.currency'])->first();
            
            if ($testAttendee) {
                $attendees = collect([$testAttendee]);
                Log::info('No attendees selected, using test attendee: ' . $testAttendee->id);
            } else {
                Log::error('No attendees found at all for company: ' . $companyId);
                return redirect()->back()->with('error', 'No attendees found for ticket printing.');
            }
        }

        // Debug: Log what we're trying to print
        Log::info('Printing tickets for attendees:', [
            'count' => $attendees->count(),
            'company_id' => $companyId,
            'attendee_ids' => $attendees->pluck('id')->toArray(),
            'attendee_names' => $attendees->pluck('full_name')->toArray()
        ]);

        try {
            // Generate PDF
            $pdf = Pdf::loadView('tickets.print-a6', compact('attendees'))
                ->setPaper([0, 0, 297.64, 419.53], 'portrait'); // A6 size in points (105x148mm)

            Log::info('PDF generated successfully');
            return $pdf->download('tickets-' . date('Y-m-d-H-i-s') . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'PDF generation failed: ' . $e->getMessage());
        }
    }

    /**
     * Show print tickets form
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

        return view('company-admin.print-tickets', compact('events', 'attendees', 'searchResults'));
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

        return view('company-admin.attendee-show', compact('attendee'));
    }
}
