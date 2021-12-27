<?php

namespace App\Presenters;

use App\Transformers\ImageTransformer;

/**
 * Class ImagePresenter.
 *
 * @package namespace App\Presenters;
 */
class ImagePresenter extends BasePresenter
{
    /**
     * @return ImageTransformer|\League\Fractal\TransformerAbstract
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function getTransformer()
    {
        return new ImageTransformer($this->data, 0, $this->user);
    }

    public function columns()
    {
        return ['*'];
    }
}
