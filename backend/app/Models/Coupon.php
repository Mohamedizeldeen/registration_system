<?php

namespace App\Models;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'expiry_date',
        'usage_count',
        'max_usage',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'discount' => 'decimal:2',
    ];

    /**
     * Get the tickets that use this coupon.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'coupon_id');
    }

    /**
     * Check if the coupon is expired.
     */
    public function isExpired()
    {
        return $this->expiry_date && Carbon::parse($this->expiry_date)->isPast();
    }

    /**
     * Check if the coupon has reached maximum usage.
     */
    public function isMaxUsageReached()
    {
        return $this->max_usage && $this->usage_count >= $this->max_usage;
    }

    /**
     * Check if the coupon is valid for use.
     */
    public function isValid()
    {
        return !$this->isExpired() && !$this->isMaxUsageReached();
    }
}
