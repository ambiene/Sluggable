<?php

namespace Ambiene\Sluggable;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    /**
     * Boot the HasSlug trait for a model.
     *
     * @return void
     */
    public static function bootHasSlug(): void
    {
        static::creating(function (Model $model) {
            $model->generateSlug();
        });

        static::updating(function (Model $model) {
            $slugSourceFields = $model->slugSourceFields();

            foreach ($slugSourceFields as $field) {
                if ($model->isDirty($field)) {
                    $model->generateSlug();
                    break;
                }
            }
        });
    }

    /**
     * Generate a slug for the model.
     *
     * @return void
     */
    protected function generateSlug(): void
    {
        $slug = $this->generator();
        $this->slug = $this->makeSlugUnique($slug);
    }

    /**
     * Slug generator.
     *
     * @return string
     */
    protected function generator(): string
    {
        $fields = $this->slugSourceFields();

        $slug = collect($fields)
            ->map(function ($field) {
                return $this->{$field};
            })
            ->implode(config("sluggable.separator", "-"));

        return Str::slug($slug);
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
            $slug = $originalSlug . "-" . $extra;
        }

        if (static::where("slug", $slug)->exists()) {
            return $this->makeSlugUnique($originalSlug, ++$extra);
        }

        return $slug;
    }

    /**
     * Get the value of the model's slug field.
     *
     * @return array
     */
    protected function slugSourceFields(): array
    {
        if (property_exists($this, "slugSource")) {
            return is_array($this->slugSource)
                ? $this->slugSource
                : [$this->slugSource];
        }

        return ["name"];
    }
}
