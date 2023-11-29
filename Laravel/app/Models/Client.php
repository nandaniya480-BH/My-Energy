<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function plans()
    {
        return $this->hasMany(ClientPlan::class, 'client_id');
    }

    public function users()
    {
        return $this->hasMany(ClientUser::class, 'client_id');
    }
}
