<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Attendee;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        $payments = Payment::with(['attendee', 'event', 'ticket'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('payment.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $events = Event::orderBy('name')->get();
        $attendees = Attendee::orderBy('first_name')->get();
        $tickets = Ticket::with(['event', 'eventZone'])->orderBy('name')->get();
        return view('payment.create', compact('events', 'attendees', 'tickets'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'attendee_id' => 'required|exists:attendees,id',
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'transaction_id' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:100',
            'status' => 'required|in:pending,completed,failed',
        ]);

        // Validate relationships
        $attendee = Attendee::findOrFail($request->attendee_id);
        $ticket = Ticket::findOrFail($request->ticket_id);
        
        if ($attendee->event_id != $request->event_id) {
            return back()->withErrors(['attendee_id' => 'The selected attendee does not belong to the selected event.']);
        }
        
        if ($ticket->event_id != $request->event_id) {
            return back()->withErrors(['ticket_id' => 'The selected ticket does not belong to the selected event.']);
        }

        $payment = Payment::create([
            'attendee_id' => $request->attendee_id,
            'event_id' => $request->event_id,
            'ticket_id' => $request->ticket_id,
            'amount' => $request->amount,
            'currency' => strtoupper($request->currency),
            'transaction_id' => $request->transaction_id,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the specified payment.
     */
    public function show($id)
    {
        $payment = Payment::with(['attendee', 'event', 'ticket'])
            ->findOrFail($id);
        return view('payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        $events = Event::orderBy('name')->get();
        $attendees = Attendee::orderBy('first_name')->get();
        $tickets = Ticket::with(['event', 'eventZone'])->orderBy('name')->get();
        return view('payment.edit', compact('payment', 'events', 'attendees', 'tickets'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'attendee_id' => 'required|exists:attendees,id',
            'event_id' => 'required|exists:events,id',
            'ticket_id' => 'required|exists:tickets,id',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'transaction_id' => 'nullable|string|max:255',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:100',
            'status' => 'required|in:pending,completed,failed',
        ]);

        // Validate relationships
        $attendee = Attendee::findOrFail($request->attendee_id);
        $ticket = Ticket::findOrFail($request->ticket_id);
        
        if ($attendee->event_id != $request->event_id) {
            return back()->withErrors(['attendee_id' => 'The selected attendee does not belong to the selected event.']);
        }
        
        if ($ticket->event_id != $request->event_id) {
            return back()->withErrors(['ticket_id' => 'The selected ticket does not belong to the selected event.']);
        }

        $payment = Payment::findOrFail($id);
        $payment->update([
            'attendee_id' => $request->attendee_id,
            'event_id' => $request->event_id,
            'ticket_id' => $request->ticket_id,
            'amount' => $request->amount,
            'currency' => strtoupper($request->currency),
            'transaction_id' => $request->transaction_id,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
        ]);

        return redirect()->route('payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        
        return redirect()->route('payments.index')
            ->with('success', 'Payment deleted successfully!');
    }

    /**
     * Process a payment (mark as completed).
     */
    public function processPayment($id)
    {
        $payment = Payment::findOrFail($id);
        
        if ($payment->status === 'completed') {
            return redirect()->route('payments.show', $id)
                ->with('warning', 'Payment is already completed.');
        }

        $payment->update([
            'status' => 'completed',
            'payment_date' => now()->toDateString(),
        ]);

        return redirect()->route('payments.show', $id)
            ->with('success', 'Payment processed successfully!');
    }

    /**
     * Mark payment as failed.
     */
    public function markAsFailed($id)
    {
        $payment = Payment::findOrFail($id);
        
        $payment->update(['status' => 'failed']);

        return redirect()->route('payments.show', $id)
            ->with('success', 'Payment marked as failed.');
    }

    /**
     * Get payment statistics.
     */
    public function getStats()
    {
        $totalPayments = Payment::where('status', 'completed')->sum('amount');
        $pendingPayments = Payment::where('status', 'pending')->count();
        $failedPayments = Payment::where('status', 'failed')->count();
        $completedPayments = Payment::where('status', 'completed')->count();

        return response()->json([
            'total_revenue' => $totalPayments,
            'pending_count' => $pendingPayments,
            'failed_count' => $failedPayments,
            'completed_count' => $completedPayments,
        ]);
    }
}
