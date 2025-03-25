<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends BaseController
{
    /**
     * @var ProductService
     */
    protected ProductService $productService;

    /**
     * DummyModel Constructor
     *
     * @param ProductService $productService
     *
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): ProductResource|\Illuminate\Http\JsonResponse
    {

        $data = Cache::rememberForever('products', function () use ($request) {
            return $this->productService->getAll($request);
        });

        return $this->sendResponse($data,ProductResource::collection($data));
    }

    public function store(ProductRequest $request): ProductResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new ProductResource($this->productService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): ProductResource
    {
        return ProductResource::make($this->productService->getById($id));
    }

    public function update(ProductRequest $request, int $id): ProductResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new ProductResource($this->productService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->productService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
