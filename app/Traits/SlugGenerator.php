<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait SlugGenerator
{
    /**
     * Boot the SlugGenerator trait.
     */
    protected static function bootSlugGenerator()
    {
        static::creating(function ($model) {
            $model->generateSlug();
        });

        static::updating(function ($model) {
            if ($model->isDirty($model->getSlugSourceColumn())) {
                $model->generateSlug();
            }
        });
    }

    /**
     * Generate unique slug
     */
    protected function generateSlug(): void
    {
        $source = $this->getSlugSourceValue();
        $slugColumn = $this->getSlugColumn();

        if (!$source) {
            return;
        }

        $slug = Str::slug($source);
        $originalSlug = $slug;
        $count = 1;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $count++;
        }

        $this->{$slugColumn} = $slug;
    }

    /**
     * Check if slug already exists
     */
    protected function slugExists(string $slug): bool
    {
        return static::where($this->getSlugColumn(), $slug)
            ->when($this->exists, function ($query) {
                $query->where('id', '!=', $this->id);
            })
            ->exists();
    }

    /**
     * Column used for slug storage
     */
    protected function getSlugColumn(): string
    {
        return $this->slugColumn ?? 'slug';
    }

    /**
     * Column used as slug source
     */
    protected function getSlugSourceColumn(): string
    {
        return $this->slugSourceColumn ?? 'name';
    }

    /**
     * Get slug source value
     */
    protected function getSlugSourceValue(): ?string
    {
        return $this->{$this->getSlugSourceColumn()};
    }
}
