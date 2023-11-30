<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    protected $casts = [
        'access' => 'json',
    ];

    public function getaccessAttribute($value)
    {
        return json_decode($value, true);
    }
}
