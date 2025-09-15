<?php

namespace App\Models;
use App\Models\Ticket;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Attendee extends Model
{
    protected $fillable = [
        'ticket_id',
        'event_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'company',
        'job_title',
        'country',
        'checked_in',
        'checked_in_at',
        'qr_code',
        'qr_code_data',
    ];

    protected $casts = [
        'checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
    ];

    /**
     * Boot the model and set up event listeners
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($attendee) {
            // Generate QR code after attendee is created
            $attendee->generateQrCode();
        });
    }

    /**
     * Get the ticket that owns this attendee.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Get the event that owns this attendee.
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * Get the payments for this attendee.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'attendee_id');
    }

    /**
     * Get the latest payment for this attendee.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'attendee_id')->latest();
    }

    /**
     * Get the full name attribute.
     */
    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    /**
     * Check in the attendee.
     */
    public function checkIn()
    {
        $this->update([
            'checked_in' => true,
            'checked_in_at' => now(),
        ]);
    }

    /**
     * Check out the attendee.
     */
    public function checkOut()
    {
        $this->update([
            'checked_in' => false,
            'checked_in_at' => null,
        ]);
    }

    /**
     * Generate and store unique QR code for this attendee
     */
    public function generateQrCode()
    {
        // Generate unique QR code data
        $qrData = $this->simple_qr_data;
        
        // Generate QR code SVG
        $qrCode = QrCode::size(200)
            ->format('svg')
            ->generate($qrData);
        
        // Store both the QR code image and the data
        $this->update([
            'qr_code' => $qrCode,
            'qr_code_data' => $qrData,
        ]);
        
        return $qrCode;
    }

    /**
     * Get or generate QR code for this attendee
     */
    public function getQrCode()
    {
        if (empty($this->qr_code)) {
            return $this->generateQrCode();
        }
        
        return $this->qr_code;
    }

    /**
     * Generate unique QR code data for this attendee
     */
    public function getQrCodeDataAttribute()
    {
        // Create a unique QR code data string containing attendee and event information
        return json_encode([
            'attendee_id' => $this->id,
            'event_id' => $this->event_id,
            'ticket_id' => $this->ticket_id,
            'name' => $this->full_name,
            'email' => $this->email,
            'check_in_url' => url("/check-in/{$this->id}"),
            'verification_hash' => hash('sha256', $this->id . $this->email . config('app.key'))
        ]);
    }

    /**
     * Generate simple QR code data for quick scanning
     */
    public function getSimpleQrDataAttribute()
    {
        // Simple format for basic QR scanners - just the check-in URL with attendee ID
        return url("/check-in/{$this->id}") . "?hash=" . substr(hash('sha256', $this->id . $this->email . config('app.key')), 0, 8);
    }
}
