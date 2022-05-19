<?php

namespace App\Filters;

use Intervention\Image\Image;
use Intervention\Image\Filters\FilterInterface;

class MediumImageFilter implements FilterInterface
{
    public function applyFilter(Image $image)
    {
        return $image->fit(180, 180)->encode('jpg', 90);
    }
}