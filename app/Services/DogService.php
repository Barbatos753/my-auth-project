<?php

namespace App\Services;

use App\Models\Dog;
use App\Models\User;
use App\Notifications\DogReceivedConfirmation;
use App\Notifications\DogTransferConfirmation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class DogService
{
    public function createDog(array $data)
    {
        try {
            $dog = Dog::create([
                'name' => $data['name'],
                'breed' => $data['breed'],
                'color' => $data['color'],
                'age' => $data['age'],
                'owner_id' => auth()->user()->id,
            ]);

            return $dog->load('owner');
        } catch (\Exception $e) {
            \Log::error('Create dog failed: '.$e->getMessage());
            throw $e;
        }
    }

    public function getAllDogs()
    {
        return Dog::with('owner')->get();
    }

    public function transferOwnership(array $data, $currentUser)
    {
        try {
            $dog = Dog::findOrFail($data['dog_id']);

            if ($dog->owner_id !== $currentUser->id) {
                throw ValidationException::withMessages([
                    'authorization' => ['You are not the owner of this dog.'],
                ]);
            }

            $newOwner = User::where('email', $data['new_owner_email'])->firstOrFail();

            $dog->owner_id = $newOwner->id;
            $dog->save();
            try {
                $currentUser->notify(new DogTransferConfirmation($dog, $newOwner));
                $newOwner->notify(new DogReceivedConfirmation($dog, $currentUser));

            } catch (\Exception $e) {
                \Log::error('Failed to send transfer emails: '.$e->getMessage());
            }

            return [
                "dog" => $dog,
                "old_owner" => $currentUser->only(['id', 'name', 'email']),
                "new_owner" => $newOwner->only(['id', 'name', 'email']),
            ];
        } catch (\Exception $e) {
            \Log::error('Transfer dog failed: '.$e->getMessage());
            throw $e;
        }
    }
    public function getDogsByOwner(int $ownerId)
    {
        return \App\Models\Dog::where('owner_id', $ownerId)->get();
    }

}
