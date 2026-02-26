<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use ReflectionClass;
use SplFileInfo;

class FileHelper
{
    /**
     * Get instantiated classes from path.
     * Classes should live under app/ directory.
     */
    public static function getClassesFromPath(string $path, ?callable $booleanCallback = null)
    {
        return collect(File::allFiles($path))
            ->map(function (SplFileInfo $file) use ($booleanCallback) {
                // Convert file path to fully qualified class name.
                $relativePath = str_replace(base_path().DIRECTORY_SEPARATOR, '', $file->getPathname());
                $class = 'App\\'.str_replace(['app'.DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, '.php'], ['', '\\', ''], $relativePath);
                if (! class_exists($class) || ! $booleanCallback || $booleanCallback(new ReflectionClass($class)) !== true) {
                    return null;
                }

                return new $class;
            })
            ->filter()
            ->values();
    }
}
