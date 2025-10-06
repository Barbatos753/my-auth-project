<?php
namespace App\Services;

use App\Events\CatCreated;
use App\Models\Cat;
use App\Models\User;
use Exception;

class CatService
{
    public function createCat(array $data)
    {
        $cat = Cat::create($data);

        $cat->owners()->attach(auth()->user()->id);
        broadcast(new CatCreated($cat))->toOthers();
        return $cat->load('owners');
    }

    public function getCats() {
        return Cat::with('owners')->get();
    }
    public function addOwner(array $data, $currentUser)
    {
        $cat = Cat::with('owners')->findOrFail($data['cat_id']);
        $newOwner = User::where('email', $data['new_owner_email'])->firstOrFail();

        if (!$cat->owners->contains($currentUser->id)) {
            throw new \Exception("You are not an owner of this cat.");
        }

        if ($cat->owners->contains($newOwner->id)) {
            throw new \Exception("This user is already an owner of the cat.");
        }

        $cat->owners()->attach($newOwner->id);

        return $cat->load('owners');
    }

    public function transferCat(array $data, $currentUser)
    {
        $cat = Cat::findOrFail($data['cat_id']);
        $newOwner = User::where('email', $data['new_owner_email'])->firstOrFail();

        if (!$cat->owners->contains($currentUser->id)) {
            throw new Exception("You are not an owner of this cat.");
        }

        $cat->owners()->detach($currentUser->id);
        $cat->owners()->attach($newOwner->id);

        return $cat->load('owners');
    }
    public function favoriteCat($catId, $user)
    {
        $cat = Cat::findOrFail($catId);

        if ($user->favoriteCats()->where('cat_id', $catId)->exists()) {
            throw new \Exception("Cat already in favorites");
        }

        $user->favoriteCats()->attach($catId);

        return $cat;
    }

    public function unfavoriteCat($catId, $user)
    {
        $cat = Cat::findOrFail($catId);

        if (!$user->favoriteCats()->where('cat_id', $catId)->exists()) {
            throw new \Exception("Cat not in favorites");
        }

        $user->favoriteCats()->detach($catId);

        return $cat;
    }

    public function getFavoriteCats($user)
    {
        return $user->favoriteCats()->get();
    }


}
