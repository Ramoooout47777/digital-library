<?php
// app/Http/Controllers/Admin/AdminSettingsController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Type;
use App\Models\Setting;
use App\Models\PrintSetting;
use App\Models\OrderSetting;
use App\Models\DiscountSetting;
use App\Models\CouponSetting;
use App\Models\NotificationSetting;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminSettingsController extends Controller
{
    /**
     * Display settings page with tabs.
     */
    public function index($tab = 'general')
    {
        // Get all data for each tab
        $types = Type::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();

        // Get general settings
        $settings = Setting::where('group', 'general')->pluck('value', 'key')->toArray();

        // Get print settings
        $printSettings = PrintSetting::first();

        // Get order settings
        $orderSettings = OrderSetting::first();

        // Get discount settings
        $discountSettings = DiscountSetting::first();

        // Get coupon settings
        $couponSettings = CouponSetting::first();

        // Get notification settings
        $notificationSettings = NotificationSetting::first();

        $activeTab = $tab;

        return view('admin.settings.index', compact(
            'types',
            'authors',
            'publishers',
            'settings',
            'printSettings',
            'orderSettings',
            'discountSettings',
            'couponSettings',
            'notificationSettings',
            'activeTab'
        ));
    }

    // ================================================================
    // GENERAL SETTINGS
    // ================================================================
    public function updateGeneral(Request $request)
    {
        $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'app_description' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'payment_qr_code' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data = $request->except(['_token', '_method', 'payment_qr_code']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key, 'group' => 'general'],
                ['value' => $value]
            );
        }

        // Handle QR Code upload
        if ($request->hasFile('payment_qr_code')) {
            // Delete old QR code if exists
            $oldQr = Setting::where('key', 'payment_qr_code')->where('group', 'general')->first();
            if ($oldQr && $oldQr->value) {
                \Storage::disk('public')->delete($oldQr->value);
            }

            $path = $request->file('payment_qr_code')->store('settings', 'public');
            Setting::updateOrCreate(
                ['key' => 'payment_qr_code', 'group' => 'general'],
                ['value' => $path]
            );
        }

        return redirect()->route('admin.settings')
            ->with('success', __('admin.settings_updated'));
    }

    // ================================================================
    // TYPE MANAGEMENT
    // ================================================================
    public function storeType(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:types'],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        Type::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.type_created') ?? 'Type created successfully');
    }

    public function updateType(Request $request, Type $type)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:types,name,' . $type->id],
            'description' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $type->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.type_updated') ?? 'Type updated successfully');
    }

    public function destroyType(Type $type)
    {
        $type->delete();

        return redirect()->route('admin.settings')
            ->with('success', __('admin.type_deleted') ?? 'Type deleted successfully');
    }

    // ================================================================
    // AUTHOR SETTINGS (Settings Tab)
    // ================================================================
    public function storeAuthor(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:authors'],
            'email' => ['nullable', 'email', 'max:255', 'unique:authors'],
            'bio' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('authors', 'public');
        }

        Author::create($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.author_created') ?? 'Author created successfully');
    }

    public function updateAuthor(Request $request, Author $author)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:authors,name,' . $author->id],
            'email' => ['nullable', 'email', 'max:255', 'unique:authors,email,' . $author->id],
            'bio' => ['nullable', 'string'],
            'website' => ['nullable', 'url', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('image')) {
            if ($author->image) {
                \Storage::disk('public')->delete($author->image);
            }
            $data['image'] = $request->file('image')->store('authors', 'public');
        }

        $author->update($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.author_updated') ?? 'Author updated successfully');
    }

    public function destroyAuthor(Author $author)
    {
        if ($author->books()->count() > 0) {
            return redirect()->route('admin.settings')
                ->with('error', __('admin.cannot_delete_has_books'));
        }

        if ($author->image) {
            \Storage::disk('public')->delete($author->image);
        }

        $author->delete();

        return redirect()->route('admin.settings')
            ->with('success', __('admin.author_deleted') ?? 'Author deleted successfully');
    }

    // ================================================================
    // PUBLISHER SETTINGS (Settings Tab)
    // ================================================================
    public function storePublisher(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:publishers'],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:publishers'],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('publishers', 'public');
        }

        Publisher::create($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.publisher_created') ?? 'Publisher created successfully');
    }

    public function updatePublisher(Request $request, Publisher $publisher)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:publishers,name,' . $publisher->id],
            'address' => ['nullable', 'string', 'max:500'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:publishers,email,' . $publisher->id],
            'website' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'status' => ['sometimes', 'boolean'],
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->has('status');

        if ($request->hasFile('logo')) {
            if ($publisher->logo) {
                \Storage::disk('public')->delete($publisher->logo);
            }
            $data['logo'] = $request->file('logo')->store('publishers', 'public');
        }

        $publisher->update($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.publisher_updated') ?? 'Publisher updated successfully');
    }

    public function destroyPublisher(Publisher $publisher)
    {
        if ($publisher->books()->count() > 0) {
            return redirect()->route('admin.settings')
                ->with('error', __('admin.cannot_delete_has_books'));
        }

        if ($publisher->logo) {
            \Storage::disk('public')->delete($publisher->logo);
        }

        $publisher->delete();

        return redirect()->route('admin.settings')
            ->with('success', __('admin.publisher_deleted') ?? 'Publisher deleted successfully');
    }

    // ================================================================
    // PRINT SETTINGS
    // ================================================================
    public function updatePrint(Request $request)
    {
        $settings = PrintSetting::firstOrCreate([]);

        $data = $request->all();
        $data['double_sided'] = $request->has('double_sided');
        $data['binding'] = $request->has('binding');
        $data['status'] = $request->has('status');

        $settings->update($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.settings_updated'));
    }

    // ================================================================
    // ORDER SETTINGS
    // ================================================================
    public function updateOrder(Request $request)
    {
        $settings = OrderSetting::firstOrCreate([]);

        $data = $request->all();
        $data['auto_confirm'] = $request->has('auto_confirm');
        $data['auto_complete'] = $request->has('auto_complete');
        $data['notify_on_new_order'] = $request->has('notify_on_new_order');
        $data['notify_on_status_change'] = $request->has('notify_on_status_change');
        $data['status'] = $request->has('status');

        $settings->update($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.settings_updated'));
    }

    // ================================================================
    // DISCOUNT SETTINGS
    // ================================================================
    public function updateDiscount(Request $request)
    {
        $settings = DiscountSetting::firstOrCreate([]);

        $data = $request->all();
        $data['auto_apply'] = $request->has('auto_apply');
        $data['status'] = $request->has('status');

        $settings->update($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.settings_updated'));
    }

    // ================================================================
    // COUPON SETTINGS
    // ================================================================
    public function updateCoupon(Request $request)
    {
        $settings = CouponSetting::firstOrCreate([]);

        $data = $request->all();
        $data['coupon_auto_apply'] = $request->has('coupon_auto_apply');
        $data['status'] = $request->has('status');

        $settings->update($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.settings_updated'));
    }

    // ================================================================
    // NOTIFICATION SETTINGS
    // ================================================================
    public function updateNotification(Request $request)
    {
        $settings = NotificationSetting::firstOrCreate([]);

        $data = [
            'email_notifications' => $request->has('email_notifications'),
            'sms_notifications' => $request->has('sms_notifications'),
            'push_notifications' => $request->has('push_notifications'),
            'in_app_notifications' => $request->has('in_app_notifications'),
            'order_notifications' => $request->has('order_notifications'),
            'payment_notifications' => $request->has('payment_notifications'),
            'promotion_notifications' => $request->has('promotion_notifications'),
            'system_notifications' => $request->has('system_notifications'),
            'security_notifications' => $request->has('security_notifications'),
            'allow_user_preferences' => $request->has('allow_user_preferences'),
            'status' => $request->has('status'),
        ];

        $settings->update($data);

        return redirect()->route('admin.settings')
            ->with('success', __('admin.settings_updated'));
    }
}
