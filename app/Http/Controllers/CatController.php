<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferCatRequest;
use App\Http\Requests\CreateCatRequest;
use App\Models\Cat;
use App\Services\CatService;
use Illuminate\Support\Facades\Log;

class CatController extends Controller
{
    protected CatService $catService;

    public function __construct(CatService $catService)
    {
        $this->catService = $catService;
    }

    public function create(CreateCatRequest $request)
    {
        try {
            $user = auth()->user();
            $data = $request->validated();

            $cat = $this->catService->createCat($data, $user);

            return $this->success(
                data: $cat,
                message: "Cat created successfully",
                code: 201
            );
        } catch (\Exception $e) {
            Log::error('Create cat failed: ' . $e->getMessage());

            return $this->error(
                message: "Failed to create cat",
                code: 500,
                data: $e->getMessage()
            );
        }
    }
    public function index()
    {
        try {
            $cats = $this->catService->getCats();

            return $this->success(
                data: $cats,
                message: "Cats retrieved successfully",
                code: 200
            );
        } catch (\Exception $e) {
            Log::error("Get cats failed: ".$e->getMessage());

            return $this->error(
                message: "Failed to retrieve cats",
                code: 500,
                data: $e->getMessage()
            );
        }
    }
    public function forceDelete($id)
    {
        $cat = Cat::onlyTrashed()->findOrFail($id);
        $cat->forceDelete();
        return response()->json(['message' => 'Cat permanently deleted']);
    }

    public function addOwner(TransferCatRequest $request)
    {
        try {
            $user = auth()->user();
            $data = $request->validated();

            $cat = $this->catService->addOwner($data, $user);

            return $this->success(
                data: $cat,
                message: "New owner added successfully",
                code: 200
            );
        } catch (\Exception $e) {
            \Log::error("Add cat owner failed: ".$e->getMessage());

            return $this->error(
                message: "Failed to add new owner",
                code: 500,
                data: $e->getMessage()
            );
        }
    }
    public function destroy($id)
    {
        $cat = Cat::findOrFail($id);
        $cat->delete();
        return response()->json(['message' => 'Cat moved to trash']);
    }
    public function trashed()
    {
        $cats = Cat::onlyTrashed()->get();
        return response()->json($cats);
    }
    public function restore($id)
    {
        $cat = Cat::onlyTrashed()->findOrFail($id);
        $cat->restore();
        return response()->json(['message' => 'Cat restored successfully']);
    }

    public function transfer(TransferCatRequest $request)
    {
        try {
            $user = auth()->user();
            $data = $request->validated();

            $result = $this->catService->transferCat($data, $user);

            return $this->success(
                data: $result,
                message: "Cat transferred successfully",
                code: 200
            );
        } catch (\Exception $e) {
            Log::error('Cat transfer failed: ' . $e->getMessage());

            return $this->error(
                message: "Failed to transfer cat",
                code: 500,
                data: $e->getMessage()
            );
        }
    }
    public function favorite($catId)
    {
        $user = auth()->user();
        try {
            $cat = $this->catService->favoriteCat($catId, $user);

            return $this->success(
                data: $cat,
                message: "Cat favorited successfully",
                code: 200
            );
        } catch (\Exception $e) {
            return $this->error(
                message: $e->getMessage(),
                code: 400
            );
        }
    }

    public function unfavorite($catId)
    {
        $user = auth()->user();
        try {
            $cat = $this->catService->unfavoriteCat($catId, $user);

            return $this->success(
                data: $cat,
                message: "Cat unfavorited successfully",
                code: 200
            );
        } catch (\Exception $e) {
            return $this->error(
                message: $e->getMessage(),
                code: 400
            );
        }
    }

    public function myFavorites()
    {
        $user = auth()->user();
        $favorites = $this->catService->getFavoriteCats($user);

        return $this->success(
            data: $favorites,
            message: "Favorite cats retrieved successfully",
            code: 200
        );
    }

}
