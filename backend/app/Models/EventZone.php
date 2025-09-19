<?php

namespace App\Models;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;

class EventZone extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'capacity',
    ];

    /**
     * Get the event that owns this zone.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the tickets for this zone.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'event_zone_id');
    }
}
