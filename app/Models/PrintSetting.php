<?php
// app/Models/PrintSetting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'print_type', 'paper_size', 'paper_type', 'print_quality',
        'copies', 'color_mode', 'double_sided', 'binding',
        'binding_type', 'price_per_page', 'setup_fee', 'shipping_fee',
        'custom_options', 'status'
    ];

    protected $casts = [
        'double_sided' => 'boolean',
        'binding' => 'boolean',
        'price_per_page' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'custom_options' => 'array',
        'status' => 'boolean',
    ];
}