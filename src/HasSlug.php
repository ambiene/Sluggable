<?php

namespace Ambiene\Sluggable;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    /**
     * Boot HasSlug trait for the model.
     *
     * @return void
     */
    public static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->generateSlug();
        });

        static::updating(function (Model $model) {
            $slugSourceField = $model->slugSourceFields();
            if ($model->isDirty($slugSourceField)) {
                $model->generateSlug();
            }
        });
    }

    /**
     * Generate the slug for the model.
     *
     * @return void
     */
    protected function generateSlug(): void
    {
        $slug = $this->generator();
        $this->slug = $this->makeSlugUnique($slug);
    }

    /**
     * Get the value of the model's slug source field.
     *
     * @return string
     */
    protected function generator(): string
    {
        $field = $this->slugSourceFields();
        $value = $this->{$field};
        return Str::slug($value, config("sluggable.separator", "-"));
    }

    /**
     * Make the slug unique.
     *
     * @param string $slug
     * @param int $extra
     * @return string
     */
    protected function makeSlugUnique(string $slug, int $extra = 0): string
    {
        $originalSlug = $slug;
        if ($extra > 0) {
            $slug = "{$originalSlug}-{$extra}";
        }
        if (static::where('slug', $slug)->exists()) {
            return $this->makeSlugUnique($originalSlug, ++$extra);
        }
        return $slug;
    }

    /**
     * Get the fields used to generate the slug.
     *
     * @return string
     */
    protected function slugSourceFields(): string
    {
        return $this->slugSource ?? 'name';
    }
}