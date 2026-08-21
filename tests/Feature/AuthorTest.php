<?php

use App\Models\Author;

it('generates a unique fallback slug for non-Latin author names', function () {
    $author = Author::create([
        'name' => 'មជ្ឈមណ្ឌលបណ្តុះធុរកិច្ចថ្មី “តេជោ”',
        'status' => true,
    ]);

    expect($author->slug)
        ->not->toBeEmpty()
        ->toStartWith('author-');
});
