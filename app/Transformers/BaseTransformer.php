<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;

abstract class BaseTransformer extends TransformerAbstract
{

    protected $user;
    protected $level;
    protected $data = [];

    const MAXIMUM_RECURSIVE = 3;

    /**
     * BaseTransformer constructor.
     *
     * @param array $data
     * @param $level
     * @param User|null $user
     *
     * @throws ValidatorException
     */
    public function __construct(array $data, $level, User $user = null)
    {
        $this->user = $user;
        if ( ! is_int($level) || $level < 0) {
            throw new \LogicException('The level is invalid for this transformer');
        }
        $this->level = $level;
        $this->data  = array_merge($this->data, $data);
        if (isset($this->data['fields'])) {
            $fields = $this->fields();
            foreach ($this->data['fields'] as $field => $values) {
                if ( ! array_key_exists($field, $fields)) {
                    $message = new MessageBag(['fields' => trans('error.field_not_exists', ['field' => $field])]);
                    throw new ValidatorException($message);
                }
            }
        }
    }

    public function isPresentField($field, $isDefault = true)
    {
        if ($this->isMaximumRecursive()) {
            return false;
        }
        if (empty($this->data['fields'])) {
            return $isDefault;
        }

        return array_key_exists($field, $this->data['fields']);
    }

    public function isMaximumRecursive()
    {
        return $this->level > static::MAXIMUM_RECURSIVE;
    }

    public function makeData($object)
    {
        $data = [];
        foreach ($this->fields() as $field => $type) {
            if ( ! $this->isPresentField($field)) {
                continue;
            }
            switch ($type) {
                case 'ignore':
                    continue;
                case 'string':
                    $data[$field] = strval($object->{$field});
                    break;
                case 'integer':
                    $data[$field] = intval($object->{$field});
                    break;
                case 'float':
                    $data[$field] = floatval($object->{$field});
                    break;
                case 'boolean':
                    $data[$field] = (bool)$object->{$field};
                    break;
                case 'date':
                    $data[$field] = $this->formatDate($object->{$field}, 'Y-m-d');
                    break;
                case 'hour':
                    $data[$field] = $this->formatDate($object->{$field}, 'h:ia');
                    break;
                case 'datetime':
                    $data[$field] = $this->formatDate($object->{$field}, 'Y-m-d H:i:s');
                    break;
                case 'timestamp':
                    $data[$field] = $this->formatDate($object->{$field}, 'timestamp');
                    break;
                default:
                    throw new \LogicException("Unknown data type for {$type} in transformer");
            }
        }

        return $data;
    }

    public function formatDate($date, $format)
    {
        if (is_null($date)) {
            return null;
        }
        if ( ! ($date instanceof Carbon)) {
            $date = new Carbon($date);
        }
        if ($format == 'timestamp') {
            return $date->getTimestamp();
        }

        return $date->format($format);
    }

    public function getData($field = null)
    {
        if (is_null($field)) {
            return $this->data;
        }
        if ( ! $this->isPresentField($field, false) || ! is_array($this->data['fields'][$field])) {
            return [];
        }

        return $this->data['fields'][$field];
    }

    public function makeTransformer($field, $transformer, array $data = [])
    {
        if ($this->isMaximumRecursive()) {
            throw new \LogicException("Can not make {$transformer} because reach to maximum recursive");
        }
        if ( ! class_exists($transformer)) {
            throw new \LogicException("Class {$transformer} is not exists");
        }
        $data     += $this->getData($field);
        $instance = new $transformer($data, $this->level + 1, $this->user);
        if ( ! ($instance instanceof TransformerAbstract)) {
            throw new \LogicException("Class {$transformer} must be an instance of TransformerAbstract");
        }

        return $instance;
    }

    abstract public function fields();
}
