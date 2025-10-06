<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dragon extends Model
{
    use HasFactory;
    protected $connection = 'mysql'; // same as .env
    protected $table = 'dragons';

    protected $fillable = ['name', 'color', 'age', 'power_level'];
}
