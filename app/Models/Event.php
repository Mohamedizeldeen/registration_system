<?php

namespace App\Models;
use App\Models\EventZone;
use App\Models\Ticket;
use App\Models\Attendee;
use App\Models\Payment;
use App\Models\Company;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
        'logo',
        'description',
        'type',
        'start_time',
        'end_time',
        'event_date',
        'event_end_date',
        'banner_url',
        'email',
        'phone',
        'location',
        'facebook',
        'instagram',
        'linkedin',
        'website',
        'company_id',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the event zones for this event.
     */
    public function eventZones()
    {
        return $this->hasMany(EventZone::class, 'event_id');
    }

    /**
     * Get the tickets for this event.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_id');
    }

    /**
     * Get the attendees for this event.
     */
    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'event_id');
    }

    /**
     * Get the payments for this event.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'event_id');
    }
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}