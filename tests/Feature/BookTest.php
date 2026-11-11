<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    private function createAdminUser(): User
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        return $admin;
    }

    public function test_user_can_view_books()
    {
        $book = Book::factory()->create([
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/api/v1/books');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'price',
                    'final_price',
                    'is_free',
                ]
            ],
            'meta'
        ]);
    }

    public function test_user_can_view_book_detail()
    {
        $book = Book::factory()->create([
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get("/api/v1/books/{$book->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $book->id,
            'title' => $book->title,
        ]);
    }

    public function test_user_can_search_books()
    {
        $book = Book::factory()->create([
            'title' => 'Laravel for Beginners',
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/api/v1/books?search=Laravel');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Laravel for Beginners',
        ]);
    }

    public function test_user_can_filter_books_by_category()
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create([
            'category_id' => $category->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get("/api/v1/books?category_id={$category->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'id' => $book->id,
        ]);
    }

    public function test_guest_can_view_web_books_index()
    {
        $book = Book::factory()->create([
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/books');

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_guest_can_view_web_book_show()
    {
        $book = Book::factory()->create([
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get("/books/{$book->slug}");

        $response->assertStatus(200);
        $response->assertSee($book->title);
    }

    public function test_admin_can_access_books_management()
    {
        $admin = $this->createAdminUser();

        $response = $this->actingAs($admin)->get('/admin/books');

        $response->assertStatus(200);
    }

    public function test_admin_can_create_book()
    {
        Storage::fake('public');

        $admin = $this->createAdminUser();
        $category = Category::factory()->create();
        $author = Author::factory()->create();
        $publisher = Publisher::factory()->create();

        $response = $this->actingAs($admin)->post('/admin/books', [
            'title' => 'New Test Book',
            'category_id' => $category->id,
            'author_id' => $author->id,
            'publisher_id' => $publisher->id,
            'price' => 19.99,
            'discount' => 5.00,
            'stock' => 10,
            'language' => 'en',
            'pages' => 250,
            'isbn' => '9781234567890',
            'pdf_file' => UploadedFile::fake()->create('book.pdf', 500, 'application/pdf'),
        ]);

        $response->assertRedirect('/admin/books');
        $this->assertDatabaseHas('books', [
            'title' => 'New Test Book',
            'final_price' => 14.99,
        ]);
    }

    public function test_admin_can_update_book()
    {
        $admin = $this->createAdminUser();
        $book = Book::factory()->create(['title' => 'Old Book Title', 'price' => 20]);

        $response = $this->actingAs($admin)->put("/admin/books/{$book->id}", [
            'title' => 'Updated Book Title',
            'category_id' => $book->category_id,
            'author_id' => $book->author_id,
            'publisher_id' => $book->publisher_id,
            'price' => 25,
            'discount' => 5,
            'stock' => $book->stock,
            'language' => $book->language,
            'pages' => $book->pages,
        ]);

        $response->assertRedirect('/admin/books');
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Book Title',
            'final_price' => 20,
        ]);
    }

    public function test_admin_can_toggle_book_status()
    {
        $admin = $this->createAdminUser();
        $book = Book::factory()->create(['status' => true]);

        $response = $this->actingAs($admin)->post("/admin/books/{$book->id}/toggle-status");

        $response->assertStatus(200);
        $response->assertJson(['success' => true, 'status' => false]);
        $this->assertDatabaseHas('books', ['id' => $book->id, 'status' => false]);
    }

    public function test_admin_can_delete_book()
    {
        $admin = $this->createAdminUser();
        $book = Book::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/books/{$book->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('books', ['id' => $book->id]);
    }

    public function test_admin_can_export_books()
    {
        $admin = $this->createAdminUser();
        Book::factory()->count(3)->create();

        $response = $this->actingAs($admin)->get('/admin/books/export');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_user_can_read_free_book_and_see_type_and_related_type_books()
    {
        Storage::fake('public');
        Storage::disk('public')->put('sample.pdf', 'dummy content');

        $type = \App\Models\Type::create(['name' => 'E-Book', 'slug' => 'e-book']);
        $book = Book::factory()->create([
            'is_free' => true,
            'pdf_file' => 'sample.pdf',
            'type_id' => $type->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $relatedBook = Book::factory()->create([
            'is_free' => true,
            'pdf_file' => 'sample.pdf',
            'type_id' => $type->id,
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get("/books/{$book->slug}/read");

        $response->assertStatus(200);
        $response->assertSee('E-Book');
        $response->assertSee($relatedBook->title);
    }

    public function test_user_can_ajax_search_books()
    {
        $book = Book::factory()->create([
            'title' => 'Advanced Laravel Mastery',
            'status' => true,
            'published_at' => now(),
        ]);

        $response = $this->get('/books/search/ajax?q=Advanced');

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'title' => 'Advanced Laravel Mastery',
            'url' => route('books.show', $book),
        ]);
    }
}
