<?php

namespace App\Models;

use App\Models\Fhir\Practitioner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

// class PractitionerUser extends Pivot
// {
//     use HasFactory;

//     protected $table = 'practitioner_user';
//     public $timestamps = false;
//     public $incrementing = true;

//     public function practitioner(): BelongsToMany
//     {
//         return $this->belongsToMany(Practitioner::class);
//     }

//     public function user(): BelongsToMany
//     {
//         return $this->belongsToMany(User::class);
//     }
// }
