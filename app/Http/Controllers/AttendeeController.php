<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    /**
     * Display a listing of attendees.
     */
    public function index(Request $request)
    {
        $query = Attendee::with(['event', 'ticket.eventZone', 'payments']);
        
        // Filter by event if event_id is provided
        if ($request->has('event_id') && $request->event_id) {
            $query->where('event_id', $request->event_id);
        }
        
        $attendees = $query->orderBy('created_at', 'desc')->get();
        return view('attendee.index', compact('attendees'));
    }

    /**
     * Export attendees to CSV format.
     */
    public function export(Request $request)
    {
        $query = Attendee::with(['event', 'ticket.eventZone', 'payments']);
        
        // Filter by event if event_id is provided
        if ($request->has('event_id') && $request->event_id) {
            $query->where('event_id', $request->event_id);
        }
        
        // Filter by search if provided
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Filter by event name if provided
        if ($request->has('event_filter') && $request->event_filter) {
            $query->whereHas('event', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->event_filter . '%');
            });
        }
        
        // Filter by check-in status if provided
        if ($request->has('status_filter') && $request->status_filter) {
            if ($request->status_filter === 'checked-in') {
                $query->where('checked_in', true);
            } elseif ($request->status_filter === 'not-checked-in') {
                $query->where('checked_in', false);
            }
        }
        
        $attendees = $query->orderBy('created_at', 'desc')->get();
        
        // Create CSV filename with timestamp
        $filename = 'attendees_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        // Set headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        // Create CSV content
        $callback = function() use ($attendees) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'First Name',
                'Last Name',
                'Email',
                'Phone',
                'Company',
                'Job Title',
                'City',
                'Country',
                'Event Name',
                'Event Date',
                'Ticket Type',
                'Ticket Price',
                'Event Zone',
                'Checked In',
                'Check-in Date',
                'Total Paid',
                'Dietary Restrictions',
                'Notes',
                'Registration Date'
            ]);
            
            // Add data rows
            foreach ($attendees as $attendee) {
                fputcsv($file, [
                    $attendee->id,
                    $attendee->first_name,
                    $attendee->last_name,
                    $attendee->email,
                    $attendee->phone,
                    $attendee->company,
                    $attendee->job_title,
                    $attendee->city,
                    $attendee->country,
                    $attendee->event->name,
                    $attendee->event->event_date instanceof \Carbon\Carbon ? $attendee->event->event_date->format('Y-m-d') : $attendee->event->event_date,
                    $attendee->ticket->name,
                    $attendee->ticket->price,
                    $attendee->ticket->eventZone->name ?? 'N/A',
                    $attendee->checked_in ? 'Yes' : 'No',
                    $attendee->checked_in_at ? $attendee->checked_in_at->format('Y-m-d H:i:s') : '',
                    $attendee->payments->sum('amount'),
                    $attendee->dietary_restrictions,
                    $attendee->notes,
                    $attendee->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show the form for creating a new attendee.
     */
    public function create()
    {
        $events = Event::orderBy('name')->get();
        $tickets = Ticket::with(['event', 'eventZone'])->orderBy('name')->get();
        return view('attendee.create', compact('events', 'tickets'));
    }

    /**
     * Store a newly created attendee in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        // Validate that ticket belongs to the selected event
        $ticket = Ticket::findOrFail($request->ticket_id);
        if ($ticket->event_id != $request->event_id) {
            return back()->withErrors(['ticket_id' => 'The selected ticket does not belong to the selected event.']);
        }

        // Check if ticket is available
        if (!$ticket->isAvailable()) {
            return back()->withErrors(['ticket_id' => 'This ticket is sold out.']);
        }

        $attendee = Attendee::create([
            'event_id' => $request->event_id,
            'ticket_id' => $request->ticket_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'job_title' => $request->job_title,
            'country' => $request->country,
        ]);

        return redirect()->route('attendees.index')
            ->with('success', 'Attendee registered successfully!');
    }

    /**
     * Display the specified attendee.
     */
    public function show($id)
    {
        $attendee = Attendee::with(['event', 'ticket.eventZone', 'payments'])
            ->findOrFail($id);
        return view('attendee.show', compact('attendee'));
    }

    /**
     * Show the form for editing the specified attendee.
     */
    public function edit($id)
    {
        $attendee = Attendee::findOrFail($id);
        $events = Event::orderBy('name')->get();
        $tickets = Ticket::with(['event', 'eventZone'])->orderBy('name')->get();
        return view('attendee.edit', compact('attendee', 'events', 'tickets'));
    }

    /**
     * Update the specified attendee in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        // Validate that ticket belongs to the selected event
        $ticket = Ticket::findOrFail($request->ticket_id);
        if ($ticket->event_id != $request->event_id) {
            return back()->withErrors(['ticket_id' => 'The selected ticket does not belong to the selected event.']);
        }

        $attendee = Attendee::findOrFail($id);
        $attendee->update([
            'event_id' => $request->event_id,
            'ticket_id' => $request->ticket_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'company' => $request->company,
            'job_title' => $request->job_title,
            'country' => $request->country,
        ]);

        return redirect()->route('attendees.index')
            ->with('success', 'Attendee updated successfully!');
    }

    /**
     * Remove the specified attendee from storage.
     */
    public function destroy($id)
    {
        $attendee = Attendee::findOrFail($id);
        
        // Check if attendee has payments
        if ($attendee->payments()->count() > 0) {
            return redirect()->route('attendees.index')
                ->with('error', 'Cannot delete attendee that has payment records.');
        }

        $attendee->delete();
        return redirect()->route('attendees.index')
            ->with('success', 'Attendee deleted successfully!');
    }

    /**
     * Get tickets for a specific event (AJAX endpoint).
     */
    public function getEventTickets($eventId)
    {
        $tickets = Ticket::with(['eventZone', 'currency'])
            ->where('event_id', $eventId)
            ->orderBy('name')
            ->get(['id', 'name', 'price', 'event_zone_id', 'currency_id'])
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'name' => $ticket->name,
                    'price' => $ticket->price,
                    'zone' => $ticket->eventZone->name ?? 'N/A',
                    'currency' => $ticket->currency->code ?? 'N/A',
                    'available' => $ticket->available_quantity,
                ];
            });

        return response()->json($tickets);
    }

    /**
     * Check in an attendee.
     */
    public function checkin(Attendee $attendee)
    {
        $attendee->checkIn();
        
        return redirect()->back()->with('success', 'Attendee checked in successfully!');
    }

    /**
     * QR Code Check-in for attendees (public access)
     */
    public function qrCheckIn(Request $request, $attendeeId)
    {
        try {
            $attendee = Attendee::with(['event', 'ticket'])->findOrFail($attendeeId);
            
            // Verify the hash if provided for security
            if ($request->has('hash')) {
                $expectedHash = substr(hash('sha256', $attendee->id . $attendee->email . config('app.key')), 0, 8);
                if ($request->hash !== $expectedHash) {
                    return view('qr.error', ['message' => 'Invalid QR code or security token.']);
                }
            }
            
            // Check in the attendee
            if (!$attendee->checked_in) {
                $attendee->checkIn();
                $message = 'Successfully checked in!';
                $status = 'success';
            } else {
                $message = 'Already checked in at ' . $attendee->checked_in_at->format('M d, Y g:i A');
                $status = 'info';
            }
            
            return view('qr.checkin', compact('attendee', 'message', 'status'));
            
        } catch (\Exception $e) {
            return view('qr.error', ['message' => 'Invalid QR code or attendee not found.']);
        }
    }
}
