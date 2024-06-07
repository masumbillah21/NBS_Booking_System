<?php 
namespace App\Helper;

use Illuminate\Support\Str;
use InvalidArgumentException;

class GenerateUniqueSlug{
    public static function slug(string $title, string $className): string
    {
        $slug = Str::slug($title);

        if (!class_exists($className)) {
            throw new InvalidArgumentException("Class '$className' does not exist.");
        }
        $instance = new $className();

        $count = $instance->where('slug', $slug)->count();

        $suffix = 1;
        while ($count > 0) {
            $newSlug = $slug . '-' . $suffix;
            $count = $instance->where('slug', $newSlug)->count();
            $suffix++;
        }

        return $newSlug ?? $slug;
    }
}