<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\User;
use App\Models\Company;
use App\Models\Attendee;
use App\Models\EventZone;
use App\Models\Ticket;
use App\Models\Payment;

class SuperAdmin extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function dashboard(){
        try {
            // Fetch real data from database
            $events = Event::with(['attendees', 'eventZones'])->get();
            $users = User::all();
            $companies = Company::all();
            $attendees = Attendee::with(['ticket', 'event'])->get();
            $eventZones = EventZone::with('event')->get();
            $tickets = Ticket::with(['event', 'eventZone', 'attendees'])->get();
            $payments = Payment::with(['attendee', 'event', 'ticket'])->get();

            // Calculate statistics
            $stats = [
                'totalEvents' => $events->count(),
                'totalUsers' => $users->count(),
                'totalCompanies' => $companies->count(),
                'totalAttendees' => $attendees->count(),
                'totalTickets' => $tickets->count(),
                'totalRevenue' => $payments->where('status', 'completed')->sum('amount'),
                'pendingPayments' => $payments->where('status', 'pending')->count(),
                'checkedInAttendees' => $attendees->where('checked_in', 1)->count(),
            ];

            $response = response()->json([
                'message' => 'Dashboard data loaded successfully',
                'timestamp' => now(),
                'stats' => $stats,
                'events' => $events,
                'users' => $users,
                'companies' => $companies,
                'attendees' => $attendees,
                'eventZones' => $eventZones,
                'tickets' => $tickets,
                'payments' => $payments,
            ], 200);

            // Add CORS headers to allow frontend access
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');

            return $response;
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Dashboard API Error',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function upcomingEvents(){
        try {
            // Get upcoming events with attendee count and revenue data
            $upcomingEvents = Event::withCount('attendees')
                ->with(['payments' => function($query) {
                    $query->where('status', 'completed');
                }])
                ->where(function($query) {
                    // Get events that are upcoming or have no date set
                    $query->where('event_date', '>=', now())
                          ->orWhereNull('event_date');
                })
                ->orderBy('event_date', 'asc')
                ->take(6) // Limit to 6 events for better performance
                ->get()
                ->map(function($event) {
                    // Calculate total revenue for each event
                    $event->total_revenue = $event->payments->sum('amount');
                    // Remove payments collection to reduce response size
                    unset($event->payments);
                    return $event;
                });

            return response()->json([
                'success' => true,
                'events' => $upcomingEvents,
                'count' => $upcomingEvents->count()
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch upcoming events',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}