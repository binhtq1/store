<?php

namespace App\Supports;


class Helper
{
    public static function getCurrentUser($guard = null)
    {
        if (!auth($guard)->check()) {
            return false;
        }

        return auth($guard)->user();
    }

    public static function getImageUrl($image, $size = 'thumbnail')
    {
        if (!$image) {
            return asset('images/no-image.png');
        }
        $imagePath = $image->path;

        if (isset($image->data[$size])) {
            $imagePath .= $image->data[$size]['name'];
        } else {
            $imagePath .= $image->data['full']['name'];
        }

        return asset($imagePath);
    }
}
