<?php

namespace App\Models;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'symbol',
        'code',
        'name',
    ];

    /**
     * Get the tickets that use this currency.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'currency_id');
    }
}
