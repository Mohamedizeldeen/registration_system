<?php

namespace App\Models;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'attendee_id',
        'event_id',
        'ticket_id',
        'amount',
        'currency',
        'transaction_id',
        'payment_date',
        'payment_method',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Get the attendee that owns this payment.
     */
    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }   

    /**
     * Get the event that owns this payment.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the ticket that owns this payment.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Check if payment is completed.
     */
    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    /**
     * Check if payment is pending.
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if payment failed.
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }

    /**
     * Get formatted amount with currency.
     */
    public function getFormattedAmountAttribute()
    {
        return $this->currency . ' ' . number_format((float) $this->amount, 2);
    }
}
