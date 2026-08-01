<?php
// app/Traits/Slugable.php

namespace App\Traits;

use Illuminate\Support\Str;

trait Slugable
{
    /**
     * Boot the trait.
     */
    protected static function bootSlugable()
    {
        static::creating(function ($model) {
            $model->generateUniqueSlug();
        });

        static::updating(function ($model) {
            if ($model->isDirty('name')) {
                $model->generateUniqueSlug();
            }
        });
    }

    /**
     * Generate a unique slug.
     */
    protected function generateUniqueSlug()
    {
        $slug = Str::slug($this->name);
        $count = static::where('slug', $slug)
            ->where('id', '!=', $this->id ?? null)
            ->count();

        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }

        $this->slug = $slug;
    }
}