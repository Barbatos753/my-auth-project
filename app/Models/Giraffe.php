<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Giraffe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'color', 'breed', 'age', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function requests()
    {
        return $this->hasMany(GiraffeRequest::class);
    }
}
