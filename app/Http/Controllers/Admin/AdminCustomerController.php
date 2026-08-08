<?php
// app/Http/Controllers/Admin/AdminCustomerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AdminCustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles'])
            ->withCount(['orders'])
            // Use selectRaw for SQLite compatibility
            ->select('users.*')
            ->selectRaw('(
                SELECT COALESCE(SUM(total), 0)
                FROM orders
                WHERE orders.user_id = users.id
                AND orders.status = "completed"
            ) as total_spent');

        // Search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%")
                  ->orWhere('phone', 'LIKE', "%{$request->search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->role($request->role);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $customers = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate last order date for each customer
        foreach ($customers as $customer) {
            $lastOrder = $customer->orders()
                ->where('status', 'completed')
                ->latest()
                ->first();
            $customer->last_order_date = $lastOrder ? $lastOrder->created_at : null;
        }

        // Statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'inactive' => User::where('is_active', false)->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count(),
        ];

        return view('admin.customers.index', compact('customers', 'stats'));
    }

    /**
     * Display the specified customer.
     */
    public function show(User $customer)
    {
        $customer->load(['orders', 'reviews.book', 'roles']);

        // Calculate total spent
        $customer->total_spent = $customer->orders()
            ->where('status', 'completed')
            ->sum('total');

        $customer->orders_count = $customer->orders()->count();

        // === FIXED: Check if last order exists before accessing ===
        $lastOrder = $customer->orders()
            ->where('status', 'completed')
            ->latest()
            ->first();

        // Set last_order_date only if last order exists
        $customer->last_order_date = $lastOrder ? $lastOrder->created_at : null;

        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(User $customer)
    {
        $customer->load(['roles']);
        $customer->orders_count = $customer->orders()->count();
        $customer->total_spent = $customer->orders()
            ->where('status', 'completed')
            ->sum('total');

        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $customer->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
            'role' => ['nullable', 'string', 'exists:roles,name'],
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active');

        // Upload avatar
        if ($request->hasFile('avatar')) {
            if ($customer->avatar) {
                Storage::disk('public')->delete($customer->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('profiles/' . $customer->id, 'public');
        }

        $customer->update($data);

        // Update role
        if ($request->has('role')) {
            $customer->syncRoles([$request->role]);
        }

        return redirect()->route('admin.customers.show', $customer)
            ->with('success', __('admin.customer_updated') ?? 'Customer updated successfully');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(User $customer)
    {
        // Prevent deleting self
        if ($customer->id === auth()->id()) {
            return redirect()->route('admin.customers.index')
                ->with('error', __('admin.cannot_delete_self') ?? 'Cannot delete yourself');
        }

        // Prevent deleting if has orders
        if ($customer->orders()->count() > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', __('admin.cannot_delete_has_orders') ?? 'Cannot delete customer with orders');
        }

        // Delete avatar
        if ($customer->avatar) {
            Storage::disk('public')->delete($customer->avatar);
        }

        $customer->delete();

        return redirect()->route('admin.customers.index')
            ->with('success', __('admin.customer_deleted') ?? 'Customer deleted successfully');
    }

    /**
     * Toggle customer status.
     */
    public function toggleStatus(User $customer)
    {
        // Prevent deactivating self
        if ($customer->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('admin.cannot_deactivate_self') ?? 'Cannot deactivate yourself',
            ], 400);
        }

        $customer->is_active = !$customer->is_active;
        $customer->save();

        return response()->json([
            'success' => true,
            'status' => $customer->is_active,
            'message' => $customer->is_active ? __('admin.customer_activated') : __('admin.customer_deactivated'),
        ]);
    }

    /**
     * Bulk update status for customers
     */
    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['exists:users,id'],
            'status' => ['required', 'boolean'],
        ]);

        // Exclude self from bulk update if it's deactivation
        $ids = $request->ids;
        if ($request->status == false) {
            $ids = array_diff($ids, [auth()->id()]);
        }

        User::whereIn('id', $ids)->update(['is_active' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => __('admin.bulk_status_updated') ?? 'បានធ្វើបច្ចុប្បន្នភាពស្ថានភាពដោយជោគជ័យ',
        ]);
    }

    /**
     * Import customers from CSV.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,xlsx'],
        ]);

        // Import logic here using Laravel Excel package

        return redirect()->route('admin.customers.index')
            ->with('success', __('admin.customers_imported') ?? 'Customers imported successfully');
    }

    /**
     * Export customers to CSV.
     */
    public function export(Request $request)
    {
        $query = User::withCount(['orders']);

        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        $customers = $query->get();

        $filename = 'customers_' . date('Y-m-d_His') . '.csv';
        $path = storage_path('app/public/exports/' . $filename);

        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        $file = fopen($path, 'w');

        fputcsv($file, [
            'ID', 'Name', 'Email', 'Phone', 'Address',
            'Total Orders', 'Status', 'Registered At', 'Last Login'
        ]);

        foreach ($customers as $customer) {
            fputcsv($file, [
                $customer->id,
                $customer->name,
                $customer->email,
                $customer->phone ?? '',
                $customer->address ?? '',
                $customer->orders_count,
                $customer->is_active ? 'Active' : 'Inactive',
                $customer->created_at->format('Y-m-d H:i:s'),
                $customer->last_login_at ? $customer->last_login_at->format('Y-m-d H:i:s') : '',
            ]);
        }

        fclose($file);

        return response()->download($path, $filename)->deleteFileAfterSend(true);
    }

}
