<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * Display a listing of currencies.
     */
    public function index()
    {
        $currencies = Currency::withCount('tickets')->orderBy('name')->get();
        return view('currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new currency.
     */
    public function create()
    {
        return view('currencies.create');
    }

    /**
     * Store a newly created currency in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'symbol' => 'required|string|max:10',
            'code' => 'required|string|max:10|unique:currencies,code',
            'name' => 'required|string|max:100',
        ]);

        Currency::create([
            'symbol' => $request->symbol,
            'code' => strtoupper($request->code),
            'name' => $request->name,
        ]);

        return redirect()->route('currencies.index')
            ->with('success', 'Currency created successfully!');
    }

    /**
     * Display the specified currency.
     */
    public function show($id)
    {
        $currency = Currency::with('tickets.event')->findOrFail($id);
        return view('currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified currency.
     */
    public function edit($id)
    {
        $currency = Currency::findOrFail($id);
        return view('currencies.edit', compact('currency'));
    }

    /**
     * Update the specified currency in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'symbol' => 'required|string|max:10',
            'code' => 'required|string|max:10|unique:currencies,code,' . $id,
            'name' => 'required|string|max:100',
        ]);

        $currency = Currency::findOrFail($id);
        $currency->update([
            'symbol' => $request->symbol,
            'code' => strtoupper($request->code),
            'name' => $request->name,
        ]);

        return redirect()->route('currencies.index')
            ->with('success', 'Currency updated successfully!');
    }

    /**
     * Remove the specified currency from storage.
     */
    public function destroy($id)
    {
        $currency = Currency::findOrFail($id);
        
        // Check if currency is being used by tickets
        if ($currency->tickets()->count() > 0) {
            return redirect()->route('currencies.index')
                ->with('error', 'Cannot delete currency that is being used by tickets.');
        }

        $currency->delete();
        return redirect()->route('currencies.index')
            ->with('success', 'Currency deleted successfully!');
    }
}
