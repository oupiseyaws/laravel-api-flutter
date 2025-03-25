<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends BaseController
{
    /**
     * @var CategoryService
     */
    protected CategoryService $categoryService;

    /**
     * DummyModel Constructor
     *
     * @param CategoryService $categoryService
     *
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): CategoryResource|\Illuminate\Http\JsonResponse
    {
        $data = Cache::rememberForever('categories', function () use ($request) {
            return $this->categoryService->getAll($request);
        });

        return $this->sendResponse($data,CategoryResource::collection($data));

    }

    public function store(CategoryRequest $request): CategoryResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new CategoryResource($this->categoryService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): CategoryResource
    {
        return CategoryResource::make($this->categoryService->getById($id));
    }

    public function update(CategoryRequest $request, int $id): CategoryResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new CategoryResource($this->categoryService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->categoryService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
