<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Str;
use Prettus\Repository\Eloquent\BaseRepository as BaseEloquentRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

abstract class BaseRepository extends BaseEloquentRepository
{

    protected $id;

    protected $skipValidator = null;

    public function getUser()
    {
        $user = auth()->guard('api')->user();
        if (!$user instanceof User) {
            throw new \LogicException(trans('error.user_invalid'));
        }

        return $user;
    }

    /**
     * Find data by id
     *
     * @param       $id
     * @param array $columns
     *
     * @param bool $findOrFail
     * @return mixed
     * @throws RepositoryException
     */
    public function find($id, $columns = ['*'], $findOrFail = true)
    {
        $this->applyCriteria();
        $this->applyScope();
        if ($findOrFail) {
            $model = $this->model->findOrFail($id, $columns);
        } else {
            $model = $this->model->find($id, $columns);
        }
        $this->resetModel();

        return $this->parserResult($model);
    }

    public function pushCriteria($criteria)
    {
        if (is_string($criteria)) {
            $criteria = app($criteria);
        }

        return parent::pushCriteria($criteria);
    }

    public function skipValidator()
    {
        $this->skipValidator = $this->validator;
        $this->validator = null;

        return $this;
    }

    public function resetValidator()
    {
        if (isset($this->skipValidator)) {
            $this->validator = $this->skipValidator;
            $this->skipValidator = null;
        }

        return $this;
    }

    /**
     * @param mixed $id
     * @return BaseRepository
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function dataTables($columns = ['*'])
    {
        $this->applyCriteria();
        $this->applyScope();

        $result = $this->model->select($columns);

        $this->resetModel();
        $this->resetScope();

        return $result;
    }

    /**
     * Retrieve all data of repository, paginated
     *
     * @param null $limit
     * @param array $columns
     * @param string $method
     *
     * @return mixed
     * @throws RepositoryException
     */
    public function paginate($limit = null, $columns = ['*'], $method = "paginate")
    {
        $this->applyCriteria();
        $this->applyScope();
        $limit = is_null($limit) ? request('limit', config('repository.pagination.limit', 15)) : $limit;
        if ($limit < 0) {
            $limit = config('repository.pagination.limit', 15);
        }
        $results = $this->model->{$method}($limit, $columns);
        $results->appends(app('request')->query());
        $this->resetModel();

        return $this->parserResult($results);
    }


    /**
     * @param $rule
     * @param array $attributes
     * @param null $id
     *
     * @return $this
     * @throws ValidatorException
     */
    public function validate($rule, array $attributes, $id = null)
    {
        if ($id) {
            $this->validator->setId($id);
        }
        $this->validator->with($attributes)->passesOrFail($rule);

        return $this;
    }

    /**
     * @param $name
     * @param string $key
     *
     * @return string
     */
    public function generateSlug($name, $key = 'slug')
    {
        $slug = Str::slug($name);

        if (!$this->id) {
            $countSlug = $this->findWhere([$key => $slug])->count();
        } else {
            $countSlug = $this->findWhere([$key => $slug, ['id', '!=', $this->id]])->count();
        }

        if (!$countSlug) {
            return $slug;
        }

        return $slug . '-' . $countSlug;
    }
}
