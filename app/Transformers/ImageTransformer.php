<?php

namespace App\Transformers;

use App\Models\Image;

/**
 * Class ImageTransformer.
 *
 * @package namespace App\Transformers;
 */
class ImageTransformer extends BaseTransformer
{
    /**
     * Transform the Image entity.
     *
     * @param Image $image
     *
     * @return array
     */
    public function transform(Image $image)
    {
        $result = ['id' => intval($image->id)];
        if ( ! empty($this->data['fields'])) {
            $result['url'] = $this->getImageUrl($image, key($this->data['fields']));
        } else {
            $result['url'] = $this->getImageUrl($image, 'full');
        }
        $result['thumbnail'] = $this->getImageUrl($image, 'small');

        return $result;
    }

    protected function getImageUrl(Image $image, $size)
    {
        $imagePath = $image->path;

        if (isset($image->data[$size])) {
            $imagePath .= $image->data[$size]['name'];
        } else {
            $imagePath .= $image->data['full']['name'];
        }

        return asset($imagePath);
    }

    public function fields()
    {
        return [
            'url'    => 'ignore',
            'full'   => 'ignore',
            'large'  => 'ignore',
            'medium' => 'ignore'
        ];
    }
}
