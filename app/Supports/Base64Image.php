<?php

namespace App\Supports;

use Dotenv\Exception\ValidationException;
use Intervention\Image\Facades\Image;
use Mockery\Exception;
use Illuminate\Support\Facades\Config;

/**
 * Class Base64Image
 * @package App\Supports
 */
class Base64Image
{
    protected $path;

    protected $sizes;

    /**
     * Base64Image constructor.
     *
     * @param $base64
     */
    public function __construct($base64)
    {
        ini_set('memory_limit', '-1');
        $this->path = 'uploads/images/' . date('Y/m') . '/';
        if ( ! is_dir($this->getPath())) {
            mkdir($this->getPath(), 0775, true);
        }
        try {
            $this->image = Image::make($base64);
        } catch (Exception $exception) {
            throw new ValidationException($exception->getMessage());
        }
        $this->saveImage();
    }

    public function getPath($name = '', $full = true): string
    {
        $path = $this->path . $name;
        if ($full) {
            return public_path($path);
        }

        return $path;
    }

    protected function saveImage()
    {
        $name = $this->getFileName();
        $this->image->backup();
        //  Save full image
        $this->image->save($this->getPath($name));
        $this->image->reset();

        $this->addSize('full', $this->image->width(), $this->image->height(), $name);
        // generate new sizes
        $this->generateSizes();
    }

    public function getExtension()
    {
        $exts = explode('/', $this->image->mime());

        return array_pop($exts);
    }

    public function getFileName($size = 'full'): string
    {
        $extension = $this->getExtension();
        $fileName  = uniqid() . '_' . $size . '.' . $extension;

        return $fileName;
    }

    protected function addSize($size, $width, $height, $name)
    {
        $this->sizes[$size] = [
            'size'      => $size,
            'mine_type' => $this->image->mime(),
            'width'     => $width,
            'height'    => $height,
            'name'      => $name,
        ];
    }

    public function getSizes(): string
    {
        return $this->sizes;
    }

    protected function generateSizes()
    {
        $sizes = Config::get('image.sizes');
        foreach ($sizes as $key => $size) {
            $width    = $size['width'];
            $height   = $size['height'];
            $fileName = $this->getFileName($width . 'x' . $height);
            $this->createNewSize($key, $width, $height, $fileName);
        }
    }

    public function createNewSize($size, $width, $height, $name): bool
    {
        if ($this->image->width() < $width) {
            return false;
        }
        $filePath = $this->getPath($name);
        $this->image->fit($width, $height);
        $this->image->save($filePath);
        $this->image->reset();
        $this->addSize($size, $width, $height, $name);

        return true;
    }

}
