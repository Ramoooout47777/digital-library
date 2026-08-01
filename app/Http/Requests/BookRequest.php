<?php
// app/Http/Requests/BookRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bookId = $this->route('book')?->id;

        return [
            'title' => ['required', 'string', 'max:255', Rule::unique('books', 'title')->ignore($bookId)],
            'category_id' => ['required', 'exists:categories,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'publisher_id' => ['required', 'exists:publishers,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_free' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'boolean'],
            'language' => ['required', 'string', 'max:10'],
            'pages' => ['required', 'integer', 'min:1'],
            'isbn' => ['nullable', 'string', 'size:13', Rule::unique('books', 'isbn')->ignore($bookId)],
            'published_at' => ['nullable', 'date'],
            'cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'pdf_file' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'file',
                'mimes:pdf',
                'max:51200'
            ],
            'sample_pdf' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'សូមបញ្ចូលចំណងជើងសៀវភៅ',
            'title.unique' => 'ចំណងជើងសៀវភៅនេះមានរួចហើយ',
            'category_id.required' => 'សូមជ្រើសរើសប្រភេទសៀវភៅ',
            'author_id.required' => 'សូមជ្រើសរើសអ្នកនិពន្ធ',
            'publisher_id.required' => 'សូមជ្រើសរើសគ្រឹះស្ថានបោះពុម្ព',
            'price.required' => 'សូមបញ្ចូលតម្លៃសៀវភៅ',
            'price.min' => 'តម្លៃមិនអាចតិចជាង 0',
            'stock.required' => 'សូមបញ្ចូលចំនួនស្តុក',
            'stock.min' => 'ចំនួនស្តុកមិនអាចតិចជាង 0',
            'pages.required' => 'សូមបញ្ចូលចំនួនទំព័រ',
            'pages.min' => 'ចំនួនទំព័រត្រូវតែច្រើនជាង 0',
            'language.required' => 'សូមជ្រើសរើសភាសា',
            'isbn.size' => 'ISBN ត្រូវតែមាន 13 ខ្ទង់',
            'isbn.unique' => 'ISBN នេះមានរួចហើយ',
            'cover.image' => 'ឯកសារត្រូវតែជារូបភាព',
            'cover.max' => 'ទំហំរូបភាពមិនអាចធំជាង 2MB',
            'pdf_file.required' => 'សូមបញ្ចូលឯកសារ PDF',
            'pdf_file.mimes' => 'ឯកសារត្រូវតែជា PDF',
            'pdf_file.max' => 'ទំហំ PDF មិនអាចធំជាង 50MB',
            'sample_pdf.max' => 'ទំហំសំណាក PDF មិនអាចធំជាង 10MB',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Auto-generate slug from title
        if ($this->has('title') && !$this->has('slug')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->title)
            ]);
        }

        // Calculate final price
        if ($this->has('price') && $this->has('discount')) {
            $price = (float) $this->price;
            $discount = (float) ($this->discount ?? 0);
            $isFree = (bool) ($this->is_free ?? false);
            
            $finalPrice = $isFree ? 0 : max(0, $price - $discount);
            $this->merge(['final_price' => $finalPrice]);
        }
    }
}