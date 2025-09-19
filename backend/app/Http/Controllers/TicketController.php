<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\EventZone;
use App\Models\Currency;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets.
     */
    public function index()
    {
        $user = Auth::user();
        $allEventswithCompany = $user->company_id ? Event::where('company_id', $user->company_id)->get() : Event::all();
        
        $ticketsQuery = Ticket::with(['event', 'eventZone', 'currency', 'coupon'])
            ->withCount('attendees')
            ->orderBy('created_at', 'desc');
        
        // If user is company admin, filter tickets by their company's events
        if ($user->role === 'admin' && $user->company_id) {
            $ticketsQuery->whereHas('event', function($query) use ($user) {
                $query->where('company_id', $user->company_id);
            });
        }
        $currencies = Currency::orderBy('name')->get();
        $tickets = $ticketsQuery->get();
        return view('ticket.index', compact('tickets', 'currencies' , 'allEventswithCompany'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create()
    {
        $user = Auth::user();
        
        // If user is company admin, only show their company's events
        if ($user->role === 'admin' && $user->company_id) {
            $events = Event::where('company_id', $user->company_id)->orderBy('name')->get();
            $eventZones = EventZone::whereHas('event', function($query) use ($user) {
                $query->where('company_id', $user->company_id);
            })->orderBy('name')->get();
        } else {
            $events = Event::orderBy('name')->get();
            $eventZones = EventZone::orderBy('name')->get();
        }
        
        $currencies = Currency::orderBy('name')->get();
        $coupons = Coupon::where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>', now());
            })
            ->where(function($query) {
                $query->whereNull('max_usage')
                      ->orWhereRaw('usage_count < max_usage');
            })
            ->orderBy('code')
            ->get();

        return view('ticket.create', compact('events', 'eventZones', 'currencies', 'coupons'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'event_zone_id' => 'required|exists:event_zones,id',
            'currency_id' => 'required|exists:currencies,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'name' => 'required|string|max:255',
            'info' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        
        // If user is company admin, ensure they can only create tickets for their company's events
        if ($user->role === 'admin' && $user->company_id) {
            $event = Event::findOrFail($request->event_id);
            if ($event->company_id !== $user->company_id) {
                abort(403, 'You can only create tickets for your company\'s events.');
            }
        }

        // Validate that event zone belongs to the selected event
        $eventZone = EventZone::findOrFail($request->event_zone_id);
        if ($eventZone->event_id != $request->event_id) {
            return back()->withErrors(['event_zone_id' => 'The selected event zone does not belong to the selected event.']);
        }

        $ticket = Ticket::create([
            'event_id' => $request->event_id,
            'event_zone_id' => $request->event_zone_id,
            'currency_id' => $request->currency_id,
            'coupon_id' => $request->coupon_id,
            'name' => $request->name,
            'info' => $request->info,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully!');
    }

    /**
     * Display the specified ticket.
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $ticket = Ticket::with(['event', 'eventZone', 'currency', 'coupon', 'attendees'])
            ->findOrFail($id);
            
        // If user is company admin, ensure they can only view tickets for their company's events
        if ($user->role === 'admin' && $user->company_id) {
            if ($ticket->event->company_id !== $user->company_id) {
                abort(403, 'You can only view tickets for your company\'s events.');
            }
        }
            
        return view('ticket.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        $ticket = Ticket::with('event')->findOrFail($id);
        
        // If user is company admin, ensure they can only edit tickets for their company's events
        if ($user->role === 'admin' && $user->company_id) {
            if ($ticket->event->company_id !== $user->company_id) {
                abort(403, 'You can only edit tickets for your company\'s events.');
            }
            
            $events = Event::where('company_id', $user->company_id)->orderBy('name')->get();
            $eventZones = EventZone::whereHas('event', function($query) use ($user) {
                $query->where('company_id', $user->company_id);
            })->orderBy('name')->get();
        } else {
            $events = Event::orderBy('name')->get();
            $eventZones = EventZone::orderBy('name')->get();
        }
        
        $currencies = Currency::orderBy('name')->get();
        $coupons = Coupon::where(function($query) {
                $query->whereNull('expiry_date')
                      ->orWhere('expiry_date', '>', now());
            })
            ->where(function($query) {
                $query->whereNull('max_usage')
                      ->orWhereRaw('usage_count < max_usage');
            })
            ->orderBy('code')
            ->get();

        return view('ticket.edit', compact('ticket', 'events', 'eventZones', 'currencies', 'coupons'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'event_zone_id' => 'required|exists:event_zones,id',
            'currency_id' => 'required|exists:currencies,id',
            'coupon_id' => 'nullable|exists:coupons,id',
            'name' => 'required|string|max:255',
            'info' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $ticket = Ticket::with('event')->findOrFail($id);
        
        // If user is company admin, ensure they can only update tickets for their company's events
        if ($user->role === 'admin' && $user->company_id) {
            if ($ticket->event->company_id !== $user->company_id) {
                abort(403, 'You can only edit tickets for your company\'s events.');
            }
            
            // Also check the new event belongs to their company
            $newEvent = Event::findOrFail($request->event_id);
            if ($newEvent->company_id !== $user->company_id) {
                abort(403, 'You can only assign tickets to your company\'s events.');
            }
        }

        // Validate that event zone belongs to the selected event
        $eventZone = EventZone::findOrFail($request->event_zone_id);
        if ($eventZone->event_id != $request->event_id) {
            return back()->withErrors(['event_zone_id' => 'The selected event zone does not belong to the selected event.']);
        }

        $ticket->update([
            'event_id' => $request->event_id,
            'event_zone_id' => $request->event_zone_id,
            'currency_id' => $request->currency_id,
            'coupon_id' => $request->coupon_id,
            'name' => $request->name,
            'info' => $request->info,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket updated successfully!');
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $ticket = Ticket::with('event')->findOrFail($id);
        
        // If user is company admin, ensure they can only delete tickets for their company's events
        if ($user->role === 'admin' && $user->company_id) {
            if ($ticket->event->company_id !== $user->company_id) {
                abort(403, 'You can only delete tickets for your company\'s events.');
            }
        }
        
        // Check if ticket has attendees
        if ($ticket->attendees()->count() > 0) {
            return redirect()->route('tickets.index')
                ->with('error', 'Cannot delete ticket that has attendees.');
        }

        $ticket->delete();
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully!');
    }    /**
     * Get event zones for a specific event (AJAX endpoint).
     */
    public function getEventZones($eventId)
    {
        $eventZones = EventZone::where('event_id', $eventId)
            ->orderBy('name')
            ->get(['id', 'name', 'capacity']);

        return response()->json($eventZones);
    }

    /**
     * Calculate ticket price with coupon discount (AJAX endpoint).
     */
    public function calculatePrice(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric|min:0',
            'coupon_id' => 'nullable|exists:coupons,id',
        ]);

        $finalPrice = $request->price;
        $discountAmount = 0;

        if ($request->coupon_id) {
            $coupon = Coupon::findOrFail($request->coupon_id);
            if ($coupon->isValid()) {
                $discountAmount = ($request->price * $coupon->discount) / 100;
                $finalPrice = $request->price - $discountAmount;
            }
        }

        return response()->json([
            'original_price' => $request->price,
            'discount_amount' => $discountAmount,
            'final_price' => $finalPrice,
        ]);
    }
}
