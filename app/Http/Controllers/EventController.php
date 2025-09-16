<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Super admin can see all events
        if ($user->role === 'super_admin') {
            $events = Event::withCount(['tickets', 'eventZones', 'attendees'])
                ->orderBy('event_date', 'desc')
                ->paginate(10);
        } else {
            // Company admin/organizer can only see their company's events
            $events = Event::where('company_id', $user->company_id)
                ->withCount(['tickets', 'eventZones', 'attendees'])
                ->orderBy('event_date', 'desc')
                ->paginate(10);
        }
        
        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Only super admin and company admin can create events
        if (!in_array($user->role, ['super_admin','admin'])) {
            abort(403, 'Access denied. Only administrators can create events.');
        }
        
        return view('events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check access control
        if (!in_array($user->role, ['super_admin' , 'admin'])) {
            abort(403, 'Access denied. Only administrators can create events.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'required|string',
            'type' => 'required|string|in:hybrid,virtual,onsite',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',      
            'event_date' => 'required|date|after_or_equal:today',
            'event_end_date' => 'required|date|after:event_date',
            'banner_url' => 'nullable|url',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
        ]);

        $eventData = $request->except(['logo']);
        
        // Set company_id for company admin
        if ($user->role === 'admin') {
            $eventData['company_id'] = $user->company_id;
        }
        
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/logos');
            $eventData['logo'] = basename($path);
        }

        $event = Event::create($eventData);

        return redirect()->route('events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Display the specified event.
     */
    public function show($id)
    {
        $user = Auth::user();
        $event = Event::with(['eventZones', 'tickets.currency', 'attendees'])
            ->findOrFail($id);
        
        // Check if user has access to this event
        if ($user->role !== 'super_admin' && $event->company_id !== $user->company_id) {
            abort(403, 'Access denied. You can only view events from your company.');
        }
        
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit($id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);
        
        // Check access control
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Access denied. Only administrators can edit events.');
        }
        
        if ($user->role !== 'super_admin' && $event->company_id !== $user->company_id) {
            abort(403, 'Access denied. You can only edit events from your company.');
        }
        
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);
        
        // Check access control
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Access denied. Only administrators can update events.');
        }
        
        if ($user->role !== 'super_admin' && $event->company_id !== $user->company_id) {
            abort(403, 'Access denied. You can only update events from your company.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'type' => 'required|string|max:100',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'event_date' => 'required|date',
            'event_end_date' => 'required|date|after:event_date',
            'banner_url' => 'nullable|url',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',   
            'linkedin' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
        ]);

        $eventData = $request->except(['logo']);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($event->logo) {
                Storage::delete('public/logos/' . $event->logo);
            }
            $path = $request->file('logo')->store('public/logos');
            $eventData['logo'] = basename($path);
        }

        $event->update($eventData);

        return redirect()->route('events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $event = Event::findOrFail($id);
        
        // Check access control
        if (!in_array($user->role, ['super_admin', 'admin'])) {
            abort(403, 'Access denied. Only administrators can delete events.');
        }
        
        if ($user->role !== 'super_admin' && $event->company_id !== $user->company_id) {
            abort(403, 'Access denied. You can only delete events from your company.');
        }
        
        // Check if event has tickets or attendees
        if ($event->tickets()->count() > 0 || $event->attendees()->count() > 0) {
            return redirect()->route('events.index')
                ->with('error', 'Cannot delete event that has tickets or attendees.');
        }

        // Delete logo file if exists
        if ($event->logo) {
            Storage::delete('public/logos/' . $event->logo);
        }

        $event->delete();

        return redirect()->route('events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
