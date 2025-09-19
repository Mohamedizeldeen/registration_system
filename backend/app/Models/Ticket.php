<?php

namespace App\Models;
use App\Models\EventZone;
use App\Models\Event;
use App\Models\Currency;
use App\Models\Coupon;
use App\Models\Attendee;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'event_zone_id',
        'event_id',
        'currency_id',
        'coupon_id',
        'name',
        'info',
        'price',
        'quantity',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the event zone that owns this ticket.
     */
    public function eventZone()
    {
        return $this->belongsTo(EventZone::class, 'event_zone_id');
    }

    /**
     * Get the event that owns this ticket.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the currency for this ticket.
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * Get the coupon for this ticket.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }

    /**
     * Get the attendees for this ticket.
     */
    public function attendees()
    {
        return $this->hasMany(Attendee::class, 'ticket_id');
    }

    /**
     * Get the payments for this ticket.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'ticket_id');
    }

    /**
     * Calculate the final price with coupon discount.
     */
    public function getFinalPriceAttribute()
    {
        $finalPrice = $this->price;
        
        if ($this->coupon && $this->coupon->isValid()) {
            $discount = ($this->price * $this->coupon->discount) / 100;
            $finalPrice = $this->price - $discount;
        }
        
        return $finalPrice;
    }

    /**
     * Get available quantity (total - sold).
     */
    public function getAvailableQuantityAttribute()
    {
        $soldQuantity = $this->attendees()->count();
        return $this->quantity - $soldQuantity;
    }

    /**
     * Check if ticket is available for purchase.
     */
    public function isAvailable()
    {
        return $this->available_quantity > 0;
    }
}
