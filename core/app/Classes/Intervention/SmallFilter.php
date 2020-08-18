<?php
namespace App\Classes\Intervention;

use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\Image;

class SmallFilter implements FilterInterface
{
    protected $height = 274;
    protected $width = 270;

    /**
     * Applies filter effects to given image
     *
     * @param  Intervention\Image\Image $image
     * @return Intervention\Image\Image
     */
    public function applyFilter(Image $image)
    {
        $image->fit($this->width, $this->height,null,'center');

        return $image;
    }
}
