<?php

namespace App\Models;

use App\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SpecimenContainerIdentifier extends Model
{
    protected $table = 'specimen_container_identifier';
    public $timestamps = false;

    public function container(): BelongsTo
    {
        return $this->belongsTo(SpecimenContainer::class, 'specimen_container_id');
    }
}
