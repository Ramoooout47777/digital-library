<?php

use App\Services\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

uses(TestCase::class);

it('uploads an image without throwing when intervention image is available', function () {
    Storage::fake('public');

    $service = new UploadService();
    $file = UploadedFile::fake()->image('book-cover.jpg', 800, 1200);

    $path = $service->uploadImage($file, 'images', [
        'width' => 400,
        'height' => 600,
        'resize' => true,
        'optimize' => true,
    ]);

    expect($path)->toStartWith('images/');
    Storage::disk('public')->assertExists($path);
});
