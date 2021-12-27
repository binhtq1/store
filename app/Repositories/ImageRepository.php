<?php

namespace App\Repositories;

use App\Models\Image;
use App\Supports\Base64Image;
use App\Validators\ImageValidator;
use Illuminate\Support\Facades\DB;
use Exception;

/**
 * Class ImageRepository
 *
 * @package App\Repositories
 */
class ImageRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Image::class;
    }

    /**
     * @return string|null
     */
    public function validator()
    {
        return ImageValidator::class;
    }

    /**
     * @param array $attributes
     *
     * @return Base64Image|mixed
     * @throws Exception
     */
    public function create(array $attributes)
    {
        try {
            DB::beginTransaction();
            $image = new Base64Image($attributes['file']);

            $attributes['path'] = $image->getPath('', false);
            $attributes['data'] = $image->getSizes();
            $attributes['description'] = isset($attributes['description']) ? $attributes['description'] : $image->getFileName();

            $image = parent::create($attributes);

            $this->assignUser($image, 1);

            DB::commit();

            return $image;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * @param $id
     * @return int
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    public function delete($id)
    {
        $image = $this->find($id);
        if (count($image->data) > 0) {
            foreach ($image->data as $file) {
                unlink($image->path . $file['name']);
            }
        }

        return parent::delete($id);
    }

    /**
     * @param Image $model
     * @param $user_id
     */
    protected function assignUser(Image $model, $user_id)
    {
        $model->user()->detach();
        $model->user()->attach($user_id);
    }
}
