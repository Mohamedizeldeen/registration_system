<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index()
    {
        $coupons = Coupon::withCount('tickets')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('coupon.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('coupon.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount' => 'required|numeric|min:0|max:100',
            'expiry_date' => 'nullable|date|after:today',
            'max_usage' => 'nullable|integer|min:1',
        ]);

        Coupon::create([
            'code' => strtoupper($request->code),
            'discount' => $request->discount,
            'expiry_date' => $request->expiry_date,
            'max_usage' => $request->max_usage,
            'usage_count' => 0,
        ]);

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon created successfully!');
    }

    /**
     * Display the specified coupon.
     */
    public function show($id)
    {
        $coupon = Coupon::with('tickets.event')->findOrFail($id);
        return view('coupon.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('coupon.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $id,
            'discount' => 'required|numeric|min:0|max:100',
            'expiry_date' => 'nullable|date',
            'max_usage' => 'nullable|integer|min:1',
        ]);

        $coupon = Coupon::findOrFail($id);
        $coupon->update([
            'code' => strtoupper($request->code),
            'discount' => $request->discount,
            'expiry_date' => $request->expiry_date,
            'max_usage' => $request->max_usage,
        ]);

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon updated successfully!');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        
        // Check if coupon is being used by tickets
        if ($coupon->tickets()->count() > 0) {
            return redirect()->route('coupons.index')
                ->with('error', 'Cannot delete coupon that is being used by tickets.');
        }

        $coupon->delete();
        return redirect()->route('coupons.index')
            ->with('success', 'Coupon deleted successfully!');
    }

    /**
     * Check if a coupon is valid and can be used.
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json(['valid' => false, 'message' => 'Coupon not found.']);
        }

        if ($coupon->expiry_date && Carbon::parse($coupon->expiry_date)->isPast()) {
            return response()->json(['valid' => false, 'message' => 'Coupon has expired.']);
        }

        if ($coupon->max_usage && $coupon->usage_count >= $coupon->max_usage) {
            return response()->json(['valid' => false, 'message' => 'Coupon usage limit reached.']);
        }

        return response()->json([
            'valid' => true,
            'discount' => $coupon->discount,
            'message' => 'Coupon is valid!'
        ]);
    }
}
