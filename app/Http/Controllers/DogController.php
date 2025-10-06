<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDogRequest;
use App\Http\Requests\TransferDogRequest;
use App\Services\DogService;
use Illuminate\Http\Request;

class DogController extends Controller
{
    protected DogService $dogService;

    public function __construct(DogService $dogService)
    {
        $this->dogService = $dogService;
    }

    public function create(CreateDogRequest $request)
    {
        try {
            $data = $request->validated();
            $dog = $this->dogService->createDog($data);

            return $this->success(
                data: $dog,
                message: "Dog created successfully",
                code: 201
            );
        } catch (\Exception $e) {
            \Log::error('Create dog failed: '.$e->getMessage());
            return $this->error(
                message: "Failed to create dog",
                code: 500,
                data: $e->getMessage()
            );
        }

    }

    public function index()
    {
        $dogs = $this->dogService->getAllDogs();

        return $this->success(
            data: $dogs,
            message: "Dogs retrieved successfully",
            code: 200
        );
    }
    public function myDogs()
    {
        $user = auth()->user();

        $dogs = $this->dogService->getDogsByOwner($user->id);

        return $this->success(
            data: $dogs,
            message: "Your dogs retrieved successfully",
            code: 200
        );
    }

    public function transferOwnership(TransferDogRequest $request)
    {
        $user = auth()->user();

        $data = $request->validated();
        $result = $this->dogService->transferOwnership($data, $user);

        return $this->success(
            data: $result,
            message: "Dog transferred successfully",
            code: 200
        );
    }

}
