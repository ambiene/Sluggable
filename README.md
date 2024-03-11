# Sluggable

This package automatically handles slug generation and ensures slugs are unique when saving or updating your models.

## Installation

After installing, you can publish the package configuration file to your project's `config` directory:

```bash
php artisan vendor:publish --provider="Ambiene\Sluggable\ServiceProvider" --tag=config
```

## Configuration

After publishing the config file, you can find it under `config/sluggable.php`. Here you can specify the default separator for slugs and other global settings for slug generation.

## Usage

To use the Sluggable trait in your Eloquent model, simply use the `HasSlug` trait and define the source fields for the slug.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Its\Sluggable\HasSlug;

class Post extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];

    protected function slugSourceFields(): string
    {
        return 'title';
    }
}
```