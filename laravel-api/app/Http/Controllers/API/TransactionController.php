<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends BaseController
{
    /**
     * @var TransactionService
     */
    protected TransactionService $transactionService;

    /**
     * DummyModel Constructor
     *
     * @param TransactionService $transactionService
     *
     */
    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $data = Cache::rememberForever('transactions', function () use ($request) {
            return $this->transactionService->getAll($request);
        });

        return $this->sendResponse($data,TransactionResource::collection($data));
    }

    public function store(TransactionRequest $request): TransactionResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new TransactionResource($this->transactionService->save($request->validated()));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(int $id): TransactionResource
    {
        return TransactionResource::make($this->transactionService->getById($id));
    }

    public function update(TransactionRequest $request, int $id): TransactionResource|\Illuminate\Http\JsonResponse
    {
        try {
            return new TransactionResource($this->transactionService->update($request->validated(), $id));
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        try {
            $this->transactionService->deleteById($id);
            return response()->json(['message' => 'Deleted successfully'], Response::HTTP_OK);
        } catch (\Exception $exception) {
            report($exception);
            return response()->json(['error' => 'There is an error.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
