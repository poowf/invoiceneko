<?php

namespace App\Traits;

trait UniqueSlug
{
    public static function generateSlug($model)
    {
        $latestSlug =
            $model::whereRaw("slug = '$model->slug' or slug LIKE '$model->slug-%'")
                ->latest('id')
                ->value('slug');

        if ($latestSlug) {
            $pieces = explode('-', $latestSlug);

            $number = intval(end($pieces));

            $model->slug .= '-'.($number + 1);
        }
    }
}
