<?php
// app/Http/Controllers/Admin/AdminCouponController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index(Request $request)
    {
        $query = Coupon::query();

        // Search
        if ($request->has('search') && $request->search) {
            $query->where('code', 'LIKE', "%{$request->search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($request->status === 'expired') {
                $query->where('expires_at', '<', now());
            }
        }

        // Filter by discount type
        if ($request->has('discount_type') && $request->discount_type) {
            $query->where('discount_type', $request->discount_type);
        }

        // Filter by expires from
        if ($request->has('expires_from') && $request->expires_from) {
            $query->whereDate('expires_at', '>=', $request->expires_from);
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(20);

        // Statistics
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('is_active', true)->count(),
            'inactive' => Coupon::where('is_active', false)->count(),
            'expired' => Coupon::where('expires_at', '<', now())->count(),
        ];

        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date', 'after:now'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->has('is_active');
        $data['used_count'] = 0;

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', __('admin.coupon_created') ?? 'Coupon created successfully');
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code,' . $coupon->id],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'min_order_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'expires_at' => ['nullable', 'date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['code'] = strtoupper($data['code']);
        $data['is_active'] = $request->has('is_active');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', __('admin.coupon_updated') ?? 'Coupon updated successfully');
    }

    /**
     * Remove the specified coupon.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', __('admin.coupon_deleted') ?? 'Coupon deleted successfully');
    }

    /**
     * Toggle coupon status.
     */
    public function toggleStatus(Coupon $coupon)
    {
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();

        return response()->json([
            'success' => true,
            'status' => $coupon->is_active,
            'message' => $coupon->is_active ? __('admin.coupon_activated') : __('admin.coupon_deactivated'),
        ]);
    }

    /**
     * Bulk update status for coupons
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:coupons,id'],
            'status' => ['required', 'boolean'],
        ]);

        Coupon::whereIn('id', $request->ids)->update(['is_active' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => __('admin.bulk_status_updated') ?? 'បានធ្វើបច្ចុប្បន្នភាពស្ថានភាពដោយជោគជ័យ',
        ]);
    }

    /**
     * Export coupons to CSV.
     */
    public function export(Request $request)
    {
        $query = Coupon::query();

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $coupons = $query->get();

        $filename = 'coupons_' . date('Y-m-d_His') . '.csv';
        $path = storage_path('app/public/exports/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $file = fopen($path, 'w');

        fputcsv($file, [
            'ID', 'Code', 'Discount Type', 'Discount Value',
            'Min Order Amount', 'Max Discount Amount', 'Usage Limit',
            'Used Count', 'Is Active', 'Expires At', 'Created At'
        ]);

        foreach ($coupons as $coupon) {
            fputcsv($file, [
                $coupon->id,
                $coupon->code,
                $coupon->discount_type,
                $coupon->discount_value,
                $coupon->min_order_amount,
                $coupon->max_discount_amount,
                $coupon->usage_limit,
                $coupon->used_count,
                $coupon->is_active ? 'Active' : 'Inactive',
                $coupon->expires_at ? $coupon->expires_at->format('Y-m-d H:i:s') : '',
                $coupon->created_at->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($file);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }
}
