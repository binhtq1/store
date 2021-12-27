<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Presenters\ImagePresenter;
use App\Repositories\ImageRepository;
use App\Validators\ImageValidator;
use Illuminate\Http\Request;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class ImagesController
 *
 * @package App\Http\Controllers\Api
 */
class ImagesController extends Controller
{
    /**
     * @var ImageRepository
     */
    protected $repository;

    /**
     * @var ImageValidator
     */
    protected $validator;

    /**
     * ImagesController constructor.
     *
     * @param ImageRepository $repository
     * @param ImageValidator $validator
     */
    public function __construct(ImageRepository $repository, ImageValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $images = $this->repository->setPresenter(ImagePresenter::class)->all();

        return $this->respond($images);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function store(Request $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $image = $this->repository->create($request->all());

            return $this->respondWithSuccess($image);
        } catch (ValidatorException $e) {
            return $this->responseWithError($e->getMessageBag());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return $this->respond($this->repository->setPresenter(ImagePresenter::class)->find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ImageValidator::RULE_UPDATE);
            $image = $this->repository->update($request->all(), $id);

            return $this->respondWithSuccess($image);
        } catch (ValidatorException $e) {
            return $this->responseWithError($e->getMessageBag());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->repository->delete($id);

        return $this->respondWithSuccess();
    }
}
