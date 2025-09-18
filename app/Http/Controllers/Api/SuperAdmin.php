<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\event;
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
        $events = event::all();
        $users = User::all();
        $companies = Company::all();
        $attendees = Attendee::all();
        $eventZones = EventZone::all();
        $tickets = Ticket::all();
        $payments = Payment::all();

        return response()->json([
            'events' => $events,
            'users' => $users,
            'companies' => $companies,
            'attendees' => $attendees,
            'eventZones' => $eventZones,
            'tickets' => $tickets,
            'payments' => $payments,
        ], 200);
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
