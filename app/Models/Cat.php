<?php
// app/Models/Cat.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cat extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'age', 'breed'];

    public function owners(): BelongsToMany {
        return $this->belongsToMany(User::class, 'cat_user');
    }
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'cat_user_favorites')->withTimestamps();
    }

}
