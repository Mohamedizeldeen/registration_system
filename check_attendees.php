<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';

// Initialize the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Now we can use Laravel models
use App\Models\Attendee;
use App\Models\Company;
use App\Models\Event;
use App\Models\User;

echo "=== Database Check ===\n";
echo "Total Companies: " . Company::count() . "\n";
echo "Total Events: " . Event::count() . "\n";
echo "Total Attendees: " . Attendee::count() . "\n";
echo "Total Users: " . User::count() . "\n\n";

echo "=== First 5 Attendees ===\n";
$attendees = Attendee::with('event.company')->take(5)->get();
foreach ($attendees as $attendee) {
    echo "ID: {$attendee->id} | Name: {$attendee->full_name} | Event: {$attendee->event->name} | Company: {$attendee->event->company->name}\n";
}

echo "\n=== Company Check ===\n";
$companies = Company::with('users')->get();
foreach ($companies as $company) {
    echo "Company: {$company->name} (ID: {$company->id})\n";
    echo "  Users: " . $company->users->pluck('email')->implode(', ') . "\n";
    echo "  Events: " . Event::where('company_id', $company->id)->count() . "\n";
    echo "  Attendees: " . Attendee::whereHas('event', function($q) use ($company) {
        $q->where('company_id', $company->id);
    })->count() . "\n\n";
}

echo "\n=== Search Test with Company Filter ===\n";
$search = 'Rosemary';

// Test each company
foreach ($companies as $company) {
    echo "Searching for '{$search}' in company '{$company->name}' (ID: {$company->id}):\n";
    
    $results = Attendee::whereHas('event', function($q) use ($company) {
        $q->where('company_id', $company->id);
    })
    ->where(function($q) use ($search) {
        $q->where('first_name', 'like', '%' . $search . '%')
          ->orWhere('last_name', 'like', '%' . $search . '%')
          ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
    })
    ->with('event')->get();
    
    echo "  Found " . $results->count() . " results\n";
    foreach ($results as $result) {
        echo "    ID: {$result->id} | Name: {$result->full_name} | Event: {$result->event->name}\n";
    }
    echo "\n";
}
