<?php
// app/Http/Requests/ReviewRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'rating.required' => 'សូមវាយតម្លៃផ្កាយ',
            'rating.min' => 'ការវាយតម្លៃត្រូវចាប់ពី 1 ផ្កាយឡើង',
            'rating.max' => 'ការវាយតម្លៃអតិបរមា 5 ផ្កាយ',
            'comment.max' => 'មតិយោបល់មិនអាចវែងជាង 1000 តួអក្សរ',
        ];
    }
}