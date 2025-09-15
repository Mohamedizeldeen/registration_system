<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Attendee;
use App\Models\Payment;
use App\Models\EventZone;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PublicRegistrationController extends Controller
{
    /**
     * Show all available events for public registration
     */
    public function showEvents()
    {
        $events = Event::with(['company'])
            ->where('event_date', '>=', now())
            ->orderBy('event_date')
            ->get();

        return view('public.events', compact('events'));
    }

    /**
     * Show ticket types and zones for a specific event
     */
    public function showTickets($eventId)
    {
        $event = Event::with(['company'])->findOrFail($eventId);
        
        // Get all zones for this event
        $zones = EventZone::where('event_id', $eventId)
            ->orderBy('name')
            ->get();
        
        // Get all tickets grouped by zone
        $ticketsByZone = Ticket::with(['eventZone', 'currency'])
            ->where('event_id', $eventId)
            ->get()
            ->groupBy('event_zone_id');

        return view('public.tickets', compact('event', 'zones', 'ticketsByZone'));
    }

    /**
     * Show registration form for a specific ticket
     */
    public function showRegistrationForm($ticketId)
    {
        $ticket = Ticket::with(['event.company', 'eventZone', 'currency'])->findOrFail($ticketId);
        
        // Check if event is still open for registration
        if ($ticket->event->event_date < now()) {
            return redirect()->back()->with('error', 'Registration for this event has closed.');
        }

        return view('public.register', compact('ticket'));
    }

    /**
     * Process the registration and create attendee
     */
    public function processRegistration(Request $request, $ticketId)
    {
        $ticket = Ticket::with(['event', 'eventZone', 'currency'])->findOrFail($ticketId);
        
        // Validate the registration data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Create the attendee
            $attendee = Attendee::create([
                'ticket_id' => $ticket->id,
                'event_id' => $ticket->event_id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'company' => $request->company,
                'job_title' => $request->job_title,
                'country' => $request->country,
                'checked_in' => false,
            ]);

            // Create a payment record (pending status)
            $payment = Payment::create([
                'attendee_id' => $attendee->id,
                'event_id' => $ticket->event_id,
                'ticket_id' => $ticket->id,
                'amount' => $ticket->price,
                'currency' => $ticket->currency->code ?? 'USD',
                'transaction_id' => 'REG-' . $attendee->id . '-' . time(),
                'payment_date' => now(),
                'payment_method' => 'pending',
                'status' => 'pending',
            ]);

            DB::commit();

            // Redirect to payment page
            return redirect()->route('public.payment', ['attendee' => $attendee->id])
                ->with('success', 'Registration successful! Please complete your payment.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show payment page for attendee
     */
    public function showPayment($attendeeId)
    {
        $attendee = Attendee::with(['event', 'ticket.eventZone', 'ticket.currency', 'payment'])
            ->findOrFail($attendeeId);

        return view('public.payment', compact('attendee'));
    }

    /**
     * Process payment (mock payment for now)
     */
    public function processPayment(Request $request, $attendeeId)
    {
        $attendee = Attendee::with(['payment'])->findOrFail($attendeeId);
        
        // Mock payment processing - in real implementation, integrate with payment gateway
        $payment = $attendee->payment;
        
        if ($payment && $payment->status === 'pending') {
            $payment->update([
                'status' => 'completed',
                'payment_method' => 'credit_card', // or whatever method
                'transaction_id' => 'TXN-' . time() . '-' . rand(1000, 9999),
            ]);

            return redirect()->route('public.confirmation', ['attendee' => $attendee->id])
                ->with('success', 'Payment completed successfully!');
        }

        return redirect()->back()->with('error', 'Payment processing failed.');
    }

    /**
     * Show registration confirmation
     */
    public function showConfirmation($attendeeId)
    {
        $attendee = Attendee::with(['event', 'ticket.eventZone', 'ticket.currency', 'payment'])
            ->findOrFail($attendeeId);

        return view('public.confirmation', compact('attendee'));
    }
}
