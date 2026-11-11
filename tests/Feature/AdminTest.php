<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Type;
use App\Models\Author;
use App\Models\Publisher;
use App\Models\Setting;
use App\Models\PrintSetting;
use App\Models\OrderSetting;
use App\Models\DiscountSetting;
use App\Models\CouponSetting;
use App\Models\NotificationSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function getAdminUser(): User
{
    Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $admin = User::factory()->create([
        'email_verified_at' => now(),
        'is_admin' => true,
    ]);
    $admin->assignRole('admin');
    return $admin;
}

// ============================================================
// ACCESS CONTROL TESTS
// ============================================================
it('redirects unauthenticated guest accessing admin settings', function () {
    $response = $this->get('/admin/settings');
    $response->assertRedirect('/login');
});

it('prevents non-admin user from accessing admin settings', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $response = $this->actingAs($user)->get('/admin/settings');
    $response->assertForbidden();
});

it('allows admin user to access admin settings dashboard', function () {
    $admin = getAdminUser();
    $response = $this->actingAs($admin)->get('/admin/settings');
    $response->assertSuccessful();
    $response->assertViewIs('admin.settings.index');
});

it('allows admin user to access specific settings tab', function () {
    $admin = getAdminUser();
    $response = $this->actingAs($admin)->get('/admin/settings/publishers');
    $response->assertSuccessful();
    $response->assertViewHas('activeTab', 'publishers');
});

// ============================================================
// GENERAL SETTINGS TESTS
// ============================================================
it('can update general settings', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->put('/admin/settings/general', [
        'app_name' => 'Updated Book Store',
        'app_description' => 'Best Online Book Store',
        'contact_email' => 'store@example.com',
        'contact_phone' => '012345678',
        'telegram_channel' => 'https://t.me/bookstore',
        'address' => 'Phnom Penh, Cambodia',
        'maintenance_mode' => '1',
        'maintenance_message' => 'Under maintenance',
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'general'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('settings', [
        'key' => 'app_name',
        'value' => json_encode('Updated Book Store'),
    ]);
    $this->assertDatabaseHas('settings', [
        'key' => 'contact_email',
        'value' => json_encode('store@example.com'),
    ]);
});

// ============================================================
// TYPE MANAGEMENT TESTS
// ============================================================
it('can store a new book type', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->post('/admin/settings/types', [
        'name' => 'Hardcover Edition',
        'description' => 'Durable hardcover books',
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'types'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('types', [
        'name' => 'Hardcover Edition',
        'slug' => 'hardcover-edition',
    ]);
});

it('can update an existing book type', function () {
    $admin = getAdminUser();
    $type = Type::create([
        'name' => 'Paperback',
        'slug' => 'paperback',
        'description' => 'Soft cover edition',
        'status' => true,
    ]);

    $response = $this->actingAs($admin)->put('/admin/settings/types/' . $type->id, [
        'name' => 'Paperback Updated',
        'description' => 'Updated soft cover edition',
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'types'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('types', [
        'id' => $type->id,
        'name' => 'Paperback Updated',
    ]);
});

it('can delete a book type', function () {
    $admin = getAdminUser();
    $type = Type::create([
        'name' => 'Audiobook',
        'slug' => 'audiobook',
        'status' => true,
    ]);

    $response = $this->actingAs($admin)->delete('/admin/settings/types/' . $type->id);

    $response->assertRedirect(route('admin.settings.tab', 'types'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('types', [
        'id' => $type->id,
    ]);
});

// ============================================================
// AUTHOR MANAGEMENT TESTS
// ============================================================
it('can store a new author', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->post('/admin/settings/authors', [
        'name' => 'George Orwell',
        'email' => 'orwell@example.com',
        'bio' => 'English novelist and essayist.',
        'website' => 'georgeorwell.org', // Test auto-prepend https://
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'authors'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('authors', [
        'name' => 'George Orwell',
        'website' => 'https://georgeorwell.org',
    ]);
});

it('can update an author', function () {
    $admin = getAdminUser();
    $author = Author::create([
        'name' => 'J.K. Rowling',
        'slug' => 'jk-rowling',
        'status' => true,
    ]);

    $response = $this->actingAs($admin)->put('/admin/settings/authors/' . $author->id, [
        'name' => 'J.K. Rowling Updated',
        'email' => 'jkrowling@example.com',
        'website' => 'https://jkrowling.com',
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'authors'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('authors', [
        'id' => $author->id,
        'name' => 'J.K. Rowling Updated',
    ]);
});

it('can delete an author with no books', function () {
    $admin = getAdminUser();
    $author = Author::create([
        'name' => 'Temporary Author',
        'slug' => 'temporary-author',
        'status' => true,
    ]);

    $response = $this->actingAs($admin)->delete('/admin/settings/authors/' . $author->id);

    $response->assertRedirect(route('admin.settings.tab', 'authors'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('authors', [
        'id' => $author->id,
    ]);
});

// ============================================================
// PUBLISHER MANAGEMENT TESTS
// ============================================================
it('can store a new publisher', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->post('/admin/settings/publishers', [
        'name' => 'Penguin Random House',
        'email' => 'contact@penguin.com',
        'phone' => '123456789',
        'website' => 'penguinrandomhouse.com',
        'address' => 'New York, USA',
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'publishers'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('publishers', [
        'name' => 'Penguin Random House',
        'website' => 'https://penguinrandomhouse.com',
    ]);
});

it('can update a publisher from settings tab', function () {
    $admin = getAdminUser();
    $publisher = Publisher::create([
        'name' => 'HarperCollins',
        'slug' => 'harpercollins',
        'status' => true,
    ]);

    $response = $this->actingAs($admin)->put('/admin/settings/publishers/' . $publisher->id, [
        'name' => 'HarperCollins International',
        'address' => 'London, UK',
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'publishers'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('publishers', [
        'id' => $publisher->id,
        'name' => 'HarperCollins International',
    ]);
});

it('can update a publisher from standalone edit view without deleting', function () {
    $admin = getAdminUser();
    $publisher = Publisher::create([
        'name' => 'Macmillan Publishers',
        'slug' => 'macmillan-publishers',
        'status' => true,
    ]);

    // Submit update form to standalone publisher route
    $response = $this->actingAs($admin)->put('/admin/publishers/' . $publisher->id, [
        'name' => 'Macmillan Updated',
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.publishers.index'));
    $response->assertSessionHas('success');

    // Ensure publisher is NOT deleted and is properly updated in DB
    $this->assertDatabaseHas('publishers', [
        'id' => $publisher->id,
        'name' => 'Macmillan Updated',
    ]);
});

it('can delete a publisher with no books', function () {
    $admin = getAdminUser();
    $publisher = Publisher::create([
        'name' => 'Old Publisher',
        'slug' => 'old-publisher',
        'status' => true,
    ]);

    $response = $this->actingAs($admin)->delete('/admin/settings/publishers/' . $publisher->id);

    $response->assertRedirect(route('admin.settings.tab', 'publishers'));
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('publishers', [
        'id' => $publisher->id,
    ]);
});

// ============================================================
// PRINT SETTINGS TESTS
// ============================================================
it('can update print settings', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->put('/admin/settings/print', [
        'print_type' => 'digital',
        'paper_size' => 'A4',
        'paper_type' => 'premium',
        'print_quality' => 'high',
        'copies' => 5,
        'color_mode' => 'color',
        'price_per_page' => 0.10,
        'setup_fee' => 2.00,
        'shipping_fee' => 5.00,
        'double_sided' => 1,
        'binding' => 1,
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'print'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('print_settings', [
        'print_type' => 'digital',
        'paper_size' => 'A4',
        'copies' => 5,
    ]);
});

// ============================================================
// ORDER SETTINGS TESTS
// ============================================================
it('can update order settings', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->put('/admin/settings/order', [
        'min_order_amount' => 5.00,
        'max_order_amount' => 500.00,
        'order_timeout' => 45,
        'payment_grace_period' => 20,
        'auto_confirm' => 1,
        'auto_complete' => 1,
        'auto_complete_days' => 7,
        'notify_on_new_order' => 1,
        'notify_on_status_change' => 1,
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'order'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('order_settings', [
        'min_order_amount' => 5.00,
        'max_order_amount' => 500.00,
        'order_timeout' => 45,
    ]);
});

// ============================================================
// DISCOUNT SETTINGS TESTS
// ============================================================
it('can update discount settings', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->put('/admin/settings/discount', [
        'default_discount' => 10.00,
        'max_discount' => 40.00,
        'discount_type' => 'percentage',
        'min_order_for_discount' => 20.00,
        'auto_apply' => 1,
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'discount'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('discount_settings', [
        'default_discount' => 10.00,
        'max_discount' => 40.00,
    ]);
});

// ============================================================
// COUPON SETTINGS TESTS
// ============================================================
it('can update coupon settings', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->put('/admin/settings/coupon', [
        'coupon_type' => 'percentage',
        'default_discount_value' => 15.00,
        'coupon_duration' => 60,
        'max_coupon_per_user' => 5,
        'min_order_for_coupon' => 10.00,
        'coupon_auto_apply' => 1,
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'coupon'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('coupon_settings', [
        'default_discount_value' => 15.00,
        'coupon_duration' => 60,
    ]);
});

// ============================================================
// NOTIFICATION SETTINGS TESTS
// ============================================================
it('can update notification settings', function () {
    $admin = getAdminUser();

    $response = $this->actingAs($admin)->put('/admin/settings/notification', [
        'email_notifications' => 1,
        'sms_notifications' => 1,
        'push_notifications' => 1,
        'in_app_notifications' => 1,
        'order_notifications' => 1,
        'payment_notifications' => 1,
        'promotion_notifications' => 1,
        'system_notifications' => 1,
        'security_notifications' => 1,
        'allow_user_preferences' => 1,
        'status' => 1,
    ]);

    $response->assertRedirect(route('admin.settings.tab', 'notification'));
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('notification_settings', [
        'email_notifications' => 1,
        'sms_notifications' => 1,
    ]);
});
