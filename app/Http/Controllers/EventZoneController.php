<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EventZone;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventZoneController extends Controller
{
    /**
     * Display a listing of event zones.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'super_admin') {
            // Super admin can see all event zones
            $eventZones = EventZone::with(['event', 'tickets'])
                ->withCount('tickets')
                ->orderBy('name')
                ->get();
        } else {
            // Company admin/organizer can only see their company's event zones
            $eventZones = EventZone::with(['event', 'tickets'])
                ->whereHas('event', function($query) use ($user) {
                    $query->where('company_id', $user->company_id);
                })
                ->withCount('tickets')
                ->orderBy('name')
                ->get();
        }
        
        return view('eventZone.index', compact('eventZones'));
    }

    /**
     * Show the form for creating a new event zone.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Only super admin and company admin can create event zones
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Access denied. Only administrators can create event zones.');
        }
        
        if ($user->role === 'super_admin') {
            // Super admin can create zones for any event
            $events = Event::orderBy('name')->get();
        } else {
            // Company admin can only create zones for their company's events
            $events = Event::where('company_id', $user->company_id)
                ->orderBy('name')
                ->get();
        }
        
        return view('eventZone.create', compact('events'));
    }

    /**
     * Store a newly created event zone in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Only super admin and company admin can create event zones
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Access denied. Only administrators can create event zones.');
        }
        
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        // Verify the event belongs to the user's company (if not super admin)
        if ($user->role !== 'super_admin') {
            $event = Event::findOrFail($request->event_id);
            if ($event->company_id !== $user->company_id) {
                abort(403, 'Access denied. You can only create zones for your company\'s events.');
            }
        }

        EventZone::create([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('eventZones.index')
            ->with('success', 'Event Zone created successfully!');
    }

    /**
     * Display the specified event zone.
     */
    public function show($id)
    {
        $user = Auth::user();
        $eventZone = EventZone::with(['event', 'tickets.currency'])
            ->findOrFail($id);
        
        // Check if user has access to this event zone
        if ($user->role !== 'super_admin' && $eventZone->event->company_id !== $user->company_id) {
            abort(403, 'Access denied. You can only view event zones from your company.');
        }
        
        return view('eventZone.show', compact('eventZone'));
    }

    /**
     * Show the form for editing the specified event zone.
     */
    public function edit($id)
    {
        $user = Auth::user();
        
        // Only super admin and company admin can edit event zones
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Access denied. Only administrators can edit event zones.');
        }
        
        $eventZone = EventZone::findOrFail($id);
        
        // Check if user has access to this event zone
        if ($user->role !== 'super_admin' && $eventZone->event->company_id !== $user->company_id) {
            abort(403, 'Access denied. You can only edit event zones from your company.');
        }
        
        if ($user->role === 'super_admin') {
            $events = Event::orderBy('name')->get();
        } else {
            $events = Event::where('company_id', $user->company_id)
                ->orderBy('name')
                ->get();
        }
        
        return view('eventZone.edit', compact('eventZone', 'events'));
    }

    /**
     * Update the specified event zone in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        // Only super admin and company admin can update event zones
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Access denied. Only administrators can update event zones.');
        }
        
        $eventZone = EventZone::findOrFail($id);
        
        // Check if user has access to this event zone
        if ($user->role !== 'super_admin' && $eventZone->event->company_id !== $user->company_id) {
            abort(403, 'Access denied. You can only update event zones from your company.');
        }
        
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'name' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:1',
        ]);

        // Verify the new event belongs to the user's company (if not super admin)
        if ($user->role !== 'super_admin') {
            $event = Event::findOrFail($request->event_id);
            if ($event->company_id !== $user->company_id) {
                abort(403, 'Access denied. You can only assign zones to your company\'s events.');
            }
        }

        $eventZone->update([
            'event_id' => $request->event_id,
            'name' => $request->name,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('eventZones.index')
            ->with('success', 'Event Zone updated successfully!');
    }

    /**
     * Remove the specified event zone from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        
        // Only super admin and company admin can delete event zones
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Access denied. Only administrators can delete event zones.');
        }
        
        $eventZone = EventZone::findOrFail($id);
        
        // Check if user has access to this event zone
        if ($user->role !== 'super_admin' && $eventZone->event->company_id !== $user->company_id) {
            abort(403, 'Access denied. You can only delete event zones from your company.');
        }
        
        // Check if zone has tickets
        if ($eventZone->tickets()->count() > 0) {
            return redirect()->route('eventZones.index')
                ->with('error', 'Cannot delete event zone that has tickets.');
        }

        $eventZone->delete();
        return redirect()->route('eventZones.index')
            ->with('success', 'Event Zone deleted successfully!');
    }
}
