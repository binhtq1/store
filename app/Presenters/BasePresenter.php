<?php

namespace App\Presenters;

use Prettus\Repository\Presenter\FractalPresenter;
use Prettus\Validator\Exceptions\ValidatorException;
use Illuminate\Support\MessageBag;
use App\Supports\FieldsParser;

abstract class BasePresenter extends FractalPresenter
{

    protected $data = [];
    protected $user = null;

    /**
     * BasePresenter constructor.
     * @param array $options
     * @throws ValidatorException
     * @throws \Exception
     */
    public function __construct(array $options = [])
    {
        parent::__construct();
        if (auth()->check()) {
            $this->user = auth()->guard()->user();
        }

        $fields = request('fields');
        if (!empty($fields)) {
            $fieldsParser = new FieldsParser();

            try {
                $this->data = array_merge($this->data, $fieldsParser->parse($fields));
            } catch (\Exception $ex) {
                $message = new MessageBag(['fields' => $ex->getMessage()]);
                throw new ValidatorException($message);
            }
        }
    }

    abstract public function columns();
}
