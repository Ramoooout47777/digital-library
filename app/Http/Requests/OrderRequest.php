<?php
// app/Http/Requests/OrderRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'book_id' => ['required', 'exists:books,id'],
            'quantity' => ['sometimes', 'integer', 'min:1'],
            'payment_method' => ['required', 'string', 'in:card,bank_transfer,paypal'],
            'coupon_code' => ['nullable', 'string', 'exists:coupons,code'],
            'shipping_address' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'book_id.required' => 'សូមជ្រើសរើសសៀវភៅ',
            'payment_method.required' => 'សូមជ្រើសើសវិធីបង់ប្រាក់',
            'coupon_code.exists' => 'លេខកូដបញ្ចុះតម្លៃមិនត្រឹមត្រូវ',
        ];
    }
}