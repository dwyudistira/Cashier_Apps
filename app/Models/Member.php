<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = "members";

    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'email',
        'join_in',
        'points',
        'member_code',
    ];

    public function members()
    {
        return $this->hasMany(Sales::class, 'member_id', 'id');
    }
}
