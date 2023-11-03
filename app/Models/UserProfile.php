<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserProfile extends Model
{
    protected $table = 'user_profile';
    public $timestamps = false;

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function practitioner(): HasOne
    {
        return $this->hasOne(Practitioner::class, 'id', 'practitioner_id');
    }
}
