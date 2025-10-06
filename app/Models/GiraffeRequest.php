<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GiraffeRequest extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'giraffe_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function giraffe()
    {
        return $this->belongsTo(Giraffe::class);
    }


    protected static function booted()
    {
        static::updated(function ($request) {
            if ($request->isDirty('status') && $request->status === 'approved') {
                $giraffe = $request->giraffe;
                if ($giraffe) {
                    $giraffe->owner_id = $request->user_id;
                    $giraffe->save();
                }
            }
        });
    }
}
