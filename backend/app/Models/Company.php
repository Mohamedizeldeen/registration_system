<?php

namespace App\Models;
use App\Models\User;
use App\Models\Event;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
    ];

    function users()
    {
        return $this->hasMany(User::class);
    }
    function events()
    {
        return $this->hasMany(Event::class);
    }
}
